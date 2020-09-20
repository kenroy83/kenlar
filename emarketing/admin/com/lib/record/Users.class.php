<?php
/**
 * This file contains record_Users class definition
 *
 * @package interspire.iem.lib.record
 */

/**
 * User Record class definition
 *
 * This class will provide encapsulation to access record level information from a database.
 * It mainly provide an interface for developers to have their code cleaner.
 *
 * NOTE:
 * - There are two valid types of user status currently available 0 and 1 to indicate "Active" and "Inactive" status.
 * - Two types of "admintype" is currently recognized a and c to indicate "System Admin" and "Regular User".
 * - Two types of "listadmintype": a and c to indicate "List Admin" and "Non list admin" -- This is hardly used, its use might need to be reconsidered.
 * - Two types of "segmentadmintype": a and c to indicate "Segment Admin" and "Non segment admin" -- This is hardly used, its use might need to be reconsidered.
 *
 * @property integer $userid User ID
 * @property string $username Username
 * @property string $password User password
 * @property string $status User status. See note above about user status
 * @property string $admintype Admin type. See note above about admin type
 * @property string $listadmintype List Admin type. See not above about list admin type
 *
 * @property integer $permonth Email limit per month
 * @property string $unlimitedmaxemails A flag noting whether or not user have unlimited email (1 for unlimited 0 otherwise)
 * @property integer $maxemails If user do not have unlimitedmaxemails, this property will hold its limit
 *
 * @property integer $credit_warning_time Time of which last warning was sent
 * @property integer $credit_warning_percentage The percentage level of credit when user received the last warning (used by monthly credit)
 * @property integer $credit_warning_fixed The credit level of credit when user received the last warning (used by fixed credit)
 *
 * @package interspire.iem.lib.record
 *
 * @todo all
 */
class record_Users extends IEM_baseRecord
{
	public function __construct($data = array())
	{
		$this->properties = array(
			'userid'					=> null,
			'username'					=> null,
			'password'					=> null,
			'status'					=> '0',
			'admintype'					=> 'c',
			'listadmintype'				=> 'c',
			'templateadmintype'			=> 'c',
			'segmentadmintype'			=> 'c',
			'fullname'					=> null,
			'emailaddress'				=> null,
			'settings'					=> null,
			'editownsettings'			=> '0',
			'usertimezone'				=> null,
			'textfooter'				=> null,
			'htmlfooter'				=> null,
			'maxlists'					=> null,
			'perhour'					=> null,
			'permonth'					=> null,
			'unlimitedmaxemails'		=> '0',
			'maxemails'					=> 0,
			'infotips'					=> '0',
			'smtpserver'				=> null,
			'smtpusername'				=> null,
			'smtppassword'				=> null,
			'smtpport'					=> 0,
			'createdate'				=> 0,
			'lastloggedin'				=> 0,
			'forgotpasscode'			=> null,
			'usewysiwyg'				=> '1',
			'xmlapi'					=> '0',
			'xmltoken'					=> null,
			'gettingstarted'			=> 0,
			'googlecalendarusername'	=> null,
			'googlecalendarpassword'	=> null,
			'user_language'				=> 'default',
			'unique_token'				=> null,
			'enableactivitylog'			=> '1',
			'eventactivitytype'			=> null,
			'forcedoubleoptin'			=> '0',
			'credit_warning_time'		=> null,
			'credit_warning_percentage'	=> null,
			'credit_warning_fixed'		=> null
		);

		parent::__construct($data);
	}
}
