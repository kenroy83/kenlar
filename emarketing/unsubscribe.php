<?php

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

$statstype = false;
$statid = 0;
$validLists = array();

$subscriberapi = $sendstudio_functions->GetApi('Subscribers');

$listapi = $sendstudio_functions->GetApi('Lists');

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

if (isset($foundparts['m'])) {
	$subscriber_id = $foundparts['m'];
} else {
	echo GetLang('InvalidUnsubscribeURL');
	exit();
}

$primary_listid = 0;
if (isset($foundparts['l'])) {
	$primary_listid = $foundparts['l'];
}

if (isset($foundparts['a'])) {
	$statstype = 'auto';
	$statid = $foundparts['a'];
} elseif (isset($foundparts['n'])) {
	$statstype = 'newsletter';
	$statid = $foundparts['n'];
}

if ($statstype) {
	$validLists = $subscriberapi->GetSubscribersListByStatOwner($statid, $subscriber_id, $statstype);
} else {
	// default
	$validLists[] = array('listid' => $primary_listid, 'subscriberid' => $subscriber_id);
}

$displayList = array();
foreach ($validLists as $eachList) {
	$listapi->Load($eachList['listid']);
	$subscriberlistinfo = $subscriberapi->LoadSubscriberList($eachList['subscriberid'], $eachList['listid']);
	$displayList[] = array('listid' => $eachList['listid'], 'name' => $listapi->Get('name'), 'cc' => $subscriberlistinfo['confirmcode'], 'subscriberid' => $eachList['subscriberid']);
}

if (!sizeof($displayList)) {
	echo GetLang('InvalidUnsubscribeURL');
	exit();
}

$GLOBALS['Message'] = '<div style="padding:10px;">'.GetLang('Unsubscribe_Form_Note').'</div>';

$tpl = GetTemplateSystem();
$tpl->Assign('page', $_GET);
$tpl->Assign('list', $displayList);
$tpl->Assign('primary_listid', $primary_listid);
echo $tpl->ParseTemplate('unsubscribe_form', true);
?>









