<?php
/**
* This file handles unsubscribe URLs and requests.
* It uses the appropriate api's to check subscribers, custom field values and lists.
*
* @see Forms_API
* @see Lists_API
* @see Subscribers_API
* @see CustomFields_API
* @see Email_API
*
* @version     $Id: unsubscribe.php,v 1.21 2008/02/13 18:41:10 tye Exp $
* @author Chris <chris@sCRiPTz-TEAM.iNFO>
*
* @package SendStudio
*/

// Make sure that the IEM controller does NOT redirect request.
if (!defined('IEM_NO_CONTROLLER')) {
	define('IEM_NO_CONTROLLER', true);
}

/**
* Require base sendstudio functionality. This connects to the database, sets up our base paths and so on.
*/
require_once dirname(__FILE__) . '/admin/index.php';

/**
* This file lets us get api's, load language files and parse templates.
*/
require_once(SENDSTUDIO_FUNCTION_DIRECTORY . '/sendstudio_functions.php');

/**
* Ignore requests from ClamAV
*/
if (isset($_SERVER['HTTP_USER_AGENT']) && strstr($_SERVER['HTTP_USER_AGENT'],'ClamAV')) { exit; }

$sendstudio_functions = new Sendstudio_Functions();
$sendstudio_functions->LoadLanguageFile('frontend');

$subscriberapi = $sendstudio_functions->GetApi('Subscribers');
$customfieldsapi = $sendstudio_functions->GetApi('CustomFields');

$listapi = $sendstudio_functions->GetApi('Lists');

$statsapi = $sendstudio_functions->GetApi('Stats');

$errors = array();

header('Content-type: text/html; charset="' . SENDSTUDIO_CHARSET . '"');

$foundparts = array();

$areas_to_check = array('M', 'C');
foreach ($areas_to_check as $p => $key) {
	if (!isset($_GET[$key])) {
		echo GetLang('InvalidUnsubscribeURL');
		exit();
	}
	$foundparts[strtolower($key)] = $_GET[$key];
}

if (isset($_GET['N'])) {
	$foundparts['n'] = (int)$_GET['N'];
}

if (isset($_GET['A'])) {
	$foundparts['a'] = (int)$_GET['A'];
}

if (isset($_GET['L'])) {
	$foundparts['l'] = (int)$_GET['L'];
}

if (isset($_GET['lists']) && is_array($_GET['lists'])) {
	$foundparts['lists'] = $_GET['lists'];
}

if (isset($foundparts['m'])) {
	$subscriber_id = $foundparts['m'];
} else {
	echo GetLang('InvalidUnsubscribeURL');
	exit();
}

if (isset($foundparts['c'])) {
	$confirmcode = $foundparts['c'];
} else {
	echo GetLang('InvalidUnsubscribeURL');
	exit();
}

/**
* Find which stats this item is for.
* We might not be clicking it from a newsletter or autoresponder,
* we might be clicking it from a form (not sure why but there you go!)
* so we can't rely on the 'a' or 'n' always being there.
*/
$statstype = false;
$statid = 0;

if (isset($foundparts['a'])) {
	$statstype = 'auto';
	$statid = $foundparts['a'];
} elseif (isset($foundparts['n'])) {
	$statstype = 'newsletter';
	$statid = $foundparts['n'];
}

/**
* Find which list this item is for.
*/
$primary_listid = 0;
if (isset($foundparts['l'])) {
	$primary_listid = $foundparts['l'];
}

if (isset($foundparts['lists'])) {
	// Custom, multiple list
	foreach ($foundparts['lists'] as $k => $v) {
		foreach ($v as $sub_key => $sub_val)
		$send_lists[] = $sub_key;
		$send_code[$k] = $sub_val;
	}
} else {
	// Default unsubscribe list
	$send_lists = array($primary_listid);
	$send_code[$subscriber_id] = $confirmcode;
}

/**
* This saves us re-loading the custom field info later on if we need to notify the list owner about the signup.
*/
$subscriberinfo = array();

$ipaddress = GetRealIp();
$unsubscribetime = $subscriberapi->GetServerTime();

/**
* Do we need to send the list owner a notification? Let's check!
*/
//$send_notification = false;

/**
* Get the email address the subscriber is using.
*
* @see Subscriber_API::GetEmailForSubscriber
*/
$email = $subscriberapi->GetEmailForSubscriber($subscriber_id);
if ($email) {
	/**
	* The primary listid is the one passed into the query string.
	* We pass this into the function so it can place that particular list at the "top".
	* We do that in case you are sending to multiple lists and decide to unsubscribe.
	* Each subscriber for each list will have a different confirmcode.
	* If the main confirmcode matches, then you are "authenticated" and will be removed from all lists at once.
	*
	* @see Subscriber_API::GetAllListsForEmailAddress
	*/
	$subscriberids = $subscriberapi->GetAllListsForEmailAddress($email, $send_lists, $primary_listid);

	$valid_subscriber = false;

	$uniqueRecipient = array();
	foreach ($subscriberids as $p => $data) {
		$subscriberid = $data['subscriberid'];
		$listid = $data['listid'];
		$listload = $listapi->Load($listid);

		if (!$listload) {
			$errors[] = sprintf(GetLang('UnsubscribeFail_InvalidList'), $listid);
			continue;
		}

		$listname = $listapi->Get('name');

		$subscriberlistinfo = $subscriberapi->LoadSubscriberList($subscriberid, $listid);
		$subscriberlistinfo['Lists'] = $listapi->Get('name');

		if (!isset($subscriberlistinfo['emailaddress']) || !isset($subscriberlistinfo['confirmcode'])) {
			$errors[] = sprintf(GetLang('ConfirmCodeDoesntMatch_Unsubscribe'), $listname);
			unset($send_lists[$p]); // take this list off the "notification" check list.
			continue;
		}

		if ($subscriberapi->IsUnsubscriber(false, $listid, $subscriberid)) {
			$errors[] = sprintf(GetLang('UnsubscribeFail_AlreadyUnsubscribed'), $listname);
			unset($send_lists[$p]); // take this list off the "notification" check list.
			continue;
		}

		$ccode = (isset($send_code[$subscriberid])) ? $send_code[$subscriberid] : $confirmcode;
		if (($subscriberlistinfo['confirmcode'] == $ccode) || $valid_subscriber) {
			$subscriberapi->Set('unsubscribeconfirmed', 1);
			$subscriberapi->Set('unsubscribeip', $ipaddress);
			$subscriberapi->UnsubscribeSubscriber(false, $listid, $subscriberid, true, $statstype, $statid);
			$subscriberinfo[$listid] = $subscriberlistinfo;

			// only record the unsubscribe for the "main" list the person was subscribed to.
			// and if there is a proper statstype ('n'ewsletter or 'a'utoresponder).
			if ($subscriberlistinfo['confirmcode'] == $ccode) {
				if ($statstype) {
					$statsapi->Unsubscribe($statid, $statstype);
				}
			}

			if (!$valid_subscriber) {
				$valid_subscriber = true;
			}

		} else {

			if (!$valid_subscriber) {
				$errors[] = sprintf(GetLang('ConfirmCodeDoesntMatch_Unsubscribe'), $listname);
				unset($send_lists[$p]); // take this list off the "notification" check list.
			}
			continue;
		}

		$notifyowner = $listapi->Get('notifyowner');
		if ($notifyowner) {
			$listowneremail = $listapi->Get('owneremail');
			$listownername = $listapi->Get('ownername');
			$uniqueRecipient[$listowneremail]['name'] = $listownername;
			$uniqueRecipient[$listowneremail]['listid'][] = $listid;
		}
	}
}

/**
* If we need to send an email notification, lets set up the email here and send it off.
*/
//if ($send_notification || 1) {
foreach ($uniqueRecipient as $uk => $uv ) {
	$emailapi = $sendstudio_functions->GetApi('Email');
	$emailapi->SetSmtp(SENDSTUDIO_SMTP_SERVER, SENDSTUDIO_SMTP_USERNAME, @base64_decode(SENDSTUDIO_SMTP_PASSWORD), SENDSTUDIO_SMTP_PORT);

	/**
	* Clear out the email and recipients just in case.
	*/
	$emailapi->ClearRecipients();
	$emailapi->ForgetEmail();
	$emailapi->Set('forcechecks', false);



	$emailapi->AddRecipient($uk, $uv['name'], 't', 0);

	$subject = GetLang('UnsubscribeNotification_Subject');
	$fieldnametype = 'UnsubscribeNotification_Field';
	$bodyname = 'UnsubscribeNotification_Body';

	$emailapi->Set('Subject', $subject);
	$emailapi->Set('FromName', false);
	$emailapi->Set('FromAddress', $email);
	$emailapi->Set('ReplyTo', $email);
	$emailapi->Set('BounceAddress', SENDSTUDIO_EMAIL_ADDRESS);

	$body = '';
	$body .= sprintf(GetLang($fieldnametype), GetLang('EmailAddress'), $email);

	$mailing_lists_body = array();

	$customfields_body = '';

	foreach ($uv['listid'] as $eachListId) {

		$p = $eachListId;
		$subinfo = $subscriberinfo[$p];

		// make sure we don't include the same info (customfield) multiple times
		// especially if the form supports multiple lists and the same fields.
		$details_already_added = array();

		if (!empty($subinfo['Lists'])) {
			if (strpos($subinfo['Lists'], ',') !== false) {
				$subinfo['Lists'] = '"' . $subinfo['Lists'] . '"';
			}
			$mailing_lists_body[] = $subinfo['Lists'];
			$customfields_body .= "\n".sprintf(GetLang($fieldnametype), 'List', $subinfo['Lists']);
		}

		foreach ($subscriberinfo[$p]['CustomFields'] as $k => $details) {
			$fieldid = $details['fieldid'];

			if (in_array($fieldid, $details_already_added)) {
				continue;
			}

			$fieldvalue = $details['data'];
			if ($fieldvalue == '') {
				$fieldvalue = GetLang('SubscriberNotification_EmptyField');
			} elseif ($details['fieldtype'] == 'checkbox') {
				$fieldvalue = implode(', ', unserialize($fieldvalue));
			}

			$fieldname = $details['fieldname'];
			$customfields_body .= sprintf(GetLang($fieldnametype), $fieldname, $fieldvalue);

			$subscriber['CustomFields'][$fieldid] = $fieldvalue;

			$details_already_added[] = $fieldid;
		}
	}

	$mailing_list_list = implode(',', $mailing_lists_body);
	$body .= sprintf(GetLang('SubscriberNotification_Lists'), $mailing_list_list);
	$emailapi->Set('Subject', sprintf(GetLang('UnsubscribeNotification_Subject_Lists'), $mailing_list_list));

	$body .= $customfields_body;

	$emailbody = sprintf(GetLang($bodyname), $body);

	$emailapi->AddBody('text', $emailbody);
	$emailapi->Set('CharSet', SENDSTUDIO_CHARSET);
	$emailapi->Send(false);
}

if (!empty($errors)) {
	$errorlist = '<br/>-' . implode('<br/>-', $errors);
	$GLOBALS['DisplayMessage'] = sprintf(GetLang('DefaultErrorMessage'), $errorlist);
} else {
	$GLOBALS['DisplayMessage'] = GetLang('DefaultUnsubscribeMessage');
}
$sendstudio_functions->ParseTemplate('Default_Form_Message');









