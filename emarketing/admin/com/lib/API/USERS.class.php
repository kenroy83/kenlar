<?php
/**
 * This file contains API_USERS static class definition.
 *
 * @package interspire.iem.lib.api
 */

/**
 * USER API static class
 *
 * This class provides an encapsulation for accessing a collection of users and users related
 * information from the database.
 *
 * @package interspire.iem.lib.api
 *
 * @todo Move all related functionalities from Users_API to this class
 */
class API_USERS extends IEM_baseAPI
{
	// --------------------------------------------------------------------------------
	// Methods needed to be extended from the parent class
	// --------------------------------------------------------------------------------
		/**
		 * Get record by ID
		 * This method will fetch a record from the database.
		 *
		 * @param integer $id Record ID to fetch
		 * @return record_Users|FALSE Returns base record if successful, FALSE otherwise
		 */
		static public function getRecordByID($id)
		{
			$userid = intval($id);
			$db = IEM::getDatabase();

			$rs = $db->Query("SELECT * FROM [|PREFIX|]users WHERE userid = {$userid}");
			if (!$rs) {
				throw new exception_IEM_baseAPI('Cannot query database -- ' . $db->Error(), exception_IEM_baseAPI::UNABLE_TO_QUERY_DATABASE);
			}

			$row = $db->Fetch($rs);
			$db->FreeResult($rs);

			if (empty($row)) {
				return false;
			}

			return new record_Users($row);
		}


		/**
		 * Delete record by ID
		 * This method will delete record from database
		 *
		 * @param integer $id ID of the record to be deleted
		 * @param boolean $deleteAllOwnedData Whether or not to delete all data associated with this user
		 *
		 * @return boolean Returns TRUE if successful, FALSE otherwise
		 */
		static public function deleteRecordByID($id, $deleteAllOwnedData = true)
		{
			if ($deleteAllOwnedData) {
				$obj = new HelperUserDelete();
				$status = $obj->deleteUsers(array($id));

				if ($status[$id]['status'] === false) {
					return false;
				} else {
					return true;
				}
			}

			$userid = intval($id);
			$db = IEM::getDatabase();

			$db->StartTransaction();

			$query = "DELETE FROM [|PREFIX|]user_permissions WHERE userid={$userid}";
			$result = $db->Query($query);
			if (!$result) {
				$db->RollbackTransaction();
				trigger_error(__CLASS__ . '::' . __METHOD__ . ' - Unable to delete user permission records' . $this->Db->Error(), E_USER_NOTICE);
				return false;
			}

			$query = "DELETE FROM [|PREFIX|]user_access WHERE userid={$userid}";
			$result = $db->Query($query);
			if (!$result) {
				$db->RollbackTransaction();
				trigger_error(__CLASS__ . '::' . __METHOD__ . ' - Unable to delete user access records' . $this->Db->Error(), E_USER_NOTICE);
				return false;
			}

			$query = "DELETE FROM [|PREFIX|]users WHERE userid={$userid}";
			$result = $db->Query($query);
			if (!$result) {
				$db->RollbackTransaction();
				trigger_error(__CLASS__ . '::' . __METHOD__ . ' - Unable to delete user record' . $this->Db->Error(), E_USER_NOTICE);
				return false;
			}

			del_user_dir($userid);

			$db->CommitTransaction();
			return true;
		}
	// --------------------------------------------------------------------------------



	/**
	 * Generate password hash
	 *
	 * @param String $password Plaintext password
	 * @param String $token User token
	 *
	 * @return String Returns a password hash
	 */
	static public function generatePasswordHash($password, $token)
	{
		return md5(md5($token) . md5($password));
	}

	/**
	 * Generate and return a new unique token
	 *
	 * A unique token generated from this method will have a maximum length of 128.
	 * It will also use the username as an additional salt to the token.
	 *
	 * @param String $username The username which the token will be generated against
	 * @return String Returns a new unique token
	 */
	static public function generateUniqueToken($username)
	{
		$token = time() . rand(10, 5000) . sha1(rand(10, 5000)) . md5(__FILE__);
		$token = str_shuffle($token);
		$token = sha1($token) . md5(microtime()) . md5($username);

		return $token;
	}


	// --------------------------------------------------------------------------------
	// Methods related to credits
	// --------------------------------------------------------------------------------
		const CREDIT_USAGETYPE_SENDCAMPAIGN			= 'send_campaign';
		const CREDIT_USAGETYPE_SENDTRIGGER			= 'send_trigger';
		const CREDIT_USAGETYPE_SENDAUTORESPONDER	= 'send_autoresponder';


		/**
		 * Record credit usage
		 * This function will record credit usage for a particular user.
		 *
		 * @param record_Users|integer $user User record object or user ID
		 * @param string $usagetype Usage type (see class constansts CREDIT_USAGETYPE_* for valid types)
		 * @param integer $creditused The number of credits that are being used up
		 * @param integer $jobid Associate job ID (OPTIONAL, default = 0)
		 * @param integer $statid Associate statistic ID (OPTIONAL, default = 0)
		 * @param integer $time Time of which the credit is being used (OPTIONAL, default = now)
		 *
		 * @return boolean Returns TRUE if successful, FALSE otherwise
		 */
		static public function creditUse($user, $usagetype, $creditused, $jobid = 0, $statid = 0, $time = 0, $evaluateWarnings = true)
		{
			// ----- Variables
				$userid = 0;
				$usagetype = strtolower($usagetype);
				$creditused = intval($creditused);
				$jobid = intval($jobid);
				$statid = intval($statid);
				$time = intval($time);

				$db = IEM::getDatabase();

				static $validTypes = null;
			// -----


			// ----- Pre
				if (is_null($validTypes)) {
					$validTypes = array(
						self::CREDIT_USAGETYPE_SENDAUTORESPONDER,
						self::CREDIT_USAGETYPE_SENDCAMPAIGN,
						self::CREDIT_USAGETYPE_SENDTRIGGER
					);
				}

				if (!($user instanceof record_Users)) {
					$userid = intval($user);
					$user = API_USERS::getRecordByID($userid);
				}


				if (!$user) {
					trigger_error("API_USERS::creditUse -- Invalid user specified.", E_USER_NOTICE);
					return false;
				}

				if (!in_array($usagetype, $validTypes)) {
					trigger_error("API_USERS::creditUse -- Invalid credit type '{$usagetype}'.", E_USER_NOTICE);
					return false;
				}

				if ($creditused < 1) {
					trigger_error("API_USERS::creditUse -- Credit cannot be less than 1.", E_USER_NOTICE);
					return false;
				}

				if ($jobid < 0) {
					trigger_error("API_USERS::creditUse -- Invalid jobid specified.", E_USER_NOTICE);
					return false;
				}

				if ($statid < 0) {
					trigger_error("API_USERS::creditUse -- Invalid statid specified.", E_USER_NOTICE);
					return false;
				}

				if ($time < 0) {
					trigger_error("API_USERS::creditUse -- Time cannot be negative.", E_USER_NOTICE);
					return false;
				}



				// If user has unlimited emails credit, we don't need to record this
				if ($user->unlimitedmaxemails && $user->permonth == 0) {
					return true;
				}

				// Check for cases (based on usage type) where credit does not need to be deducted
				switch ($usagetype) {
					case self::CREDIT_USAGETYPE_SENDTRIGGER:
						if (!SENDSTUDIO_CREDIT_INCLUDE_TRIGGERS) {
							return true;
						}
					break;

					case self::CREDIT_USAGETYPE_SENDAUTORESPONDER:
						if (!SENDSTUDIO_CREDIT_INCLUDE_AUTORESPONDERS) {
							return true;
						}
					break;
				}
			// -----



			$time = ($time == 0 ? time() : $time);

			$db->StartTransaction();

			// ----- Record this in credit table
				$tempStatus = $db->Query("
					INSERT INTO [|PREFIX|]user_credit (userid, transactiontype, transactiontime, credit, jobid, statid)
					VALUES ({$userid}, '{$usagetype}', {$time}, -{$creditused}, {$jobid}, {$statid})
				");

				if (!$tempStatus) {
					$db->RollbackTransaction();
					trigger_error("API_USERS::creditUse -- Unable to insert credit usage into database: " . $db->Error(), E_USER_NOTICE);
					return false;
				}
			// -----

			// ----- Record this in the credit summary table
				$tempTimeperiod = mktime(0, 0, 0, date('n'), 1, date('Y'));
				$tempQuery;

				// Since MySQL have a direct query which will insert/update in one go, we can utilzie this.
				if (SENDSTUDIO_DATABASE_TYPE == 'mysql') {
					$tempQuery = "
						INSERT INTO [|PREFIX|]user_credit_summary (userid, startperiod, credit_used)
						VALUES ({$userid}, {$tempTimeperiod}, {$creditused})
						ON DUPLICATE KEY UPDATE credit_used = credit_used + {$creditused}
					";


				// Do we need to do an INSERT or an UPDATE query ??
				} else {
					$tempRS = $db->Query("SELECT usagesummaryid FROM [|PREFIX|]user_credit_summary WHERE userid = {$userid} AND startperiod = {$tempTimeperiod}");
					if (!$tempRS) {
						$db->RollbackTransaction();
						trigger_error("API_USERS::creditUse -- Cannot query user_credit_summary table: " . $db->Error(), E_USER_NOTICE);
						return false;
					}

					if ($db->CountResult($tempRS) == 0) {
						$tempQuery = "
							INSERT INTO [|PREFIX|]user_credit_summary (userid, startperiod, credit_used)
							VALUES ({$userid}, {$tempTimeperiod}, {$creditused})
						";
					} else {
						$tempSummaryID = $db->FetchOne($tempRS, 'usagesummaryid');

						$tempQuery = "
							UPDATE [|PREFIX|]user_credit_summary
							SET credit_used = credit_used + {$creditused}
							WHERE usagesummaryid = {$tempSummaryID}
						";
					}

					$db->FreeResult($tempRS);
				}

				$tempStatus = $db->Query($tempQuery);
				if (!$tempStatus) {
					$db->RollbackTransaction();
					trigger_error("API_USERS::creditUse -- Unable to update/insert user_credit_summary table: " . $db->Error(), E_USER_NOTICE);
					return false;
				}
			// -----

			// -----
			// Since we are still using some credit counting code, we also need to update these.
			// Although the system will allow you to have both credit system (fixed and monthly) enabled on an account,
			// it will deduct credit from BOTH.
			//
			// TODO: remove these code once all code related to credit has been reviewed and refactored (IEM 6.0.0 ??)
			// TODO: Add pieces of code for fix cedits
			// -----
				// Only update maxemails when user don't have unlimitedmaxemails
				if ($user->unlimitedmaxemails != '1') {
					$tempMaxEmails = $user->maxemails - $creditused;
					if ($tempMaxEmails < 0) {
						$tempMaxEmails = 0;
					}

					$tempStatus = $db->Query("UPDATE [|PREFIX|]users SET maxemails = {$tempMaxEmails} WHERE userid = {$userid}");
					if (!$tempStatus) {
						$db->RollbackTransaction();
						trigger_error("API_USERS::creditUse -- Cannot update user's maxemails: " . $db->Error(), E_USER_NOTICE);
						return false;
					}

					$tempCurrentUser = IEM::getCurrentUser();
					if ($tempCurrentUser && $tempCurrentUser->userid == $userid) {
						$tempCurrentUser->maxemails = $tempMaxEmails;
					}
				}
			// -----

			$db->CommitTransaction();

			if ($evaluateWarnings) {
				return self::creditEvaluateWarnings($userid);
			} else {
				return true;
			}
		}

		/**
		 *
		 * @param $user
		 * @param $credit
		 * @param $expiry
		 * @return unknown_type
		 *
		 * @todo all
		 */
		static public function creditAdd($user, $credit, $expiry)
		{

		}

		/**
		 * Get available monthy credit for this month
		 *
		 * @param record_Users|integer $user User record object or user ID
		 * @param boolean $percentage Whether or not you want to return the available credit as a percentage
		 * @return integer|boolean Returns TRUE if user has unlimited credit, an integer if user has a limit, FALSE if it encountered any error
		 */
		static public function creditAvailableThisMonth($user, $percentage = false)
		{
			$db = IEM::getDatabase();
			$userobject = null;
			$credits = array();


			// ----- PRE
				if ($user instanceof record_Users) {
					$userobject = $user;
				} else {
					$userobject = self::getRecordByID($user);
				}

				if (empty($userobject)) {
					trigger_error(__CLASS__ . '::' . __METHOD__ . ' -- User is not specified', E_USER_NOTICE);
					return false;
				}

				// No limit, returns TRUE
				if ($userobject->permonth == 0) {
					return true;
				}
			// -----


			// ----- Get credit used this month from database
				// FIXME this is using older non-GMT time
				$tempThisMonth = AdjustTime(array (0, 0, 1, date('m') ,1, date('Y')), true, null, true);
				$tempNextMonth = AdjustTime(array (0, 0, 1, (date('m') + 1) , 1, date('Y')), true, null, true);

				$tempStartPeriod = mktime(0, 0, 0, date('m'), 1, date('Y'));

				$tempQuery = "
					SELECT	SUM(queuesize) AS credit_used
					FROM	[|PREFIX|]stats_users
					WHERE	userid = {$userobject->userid}
							AND queuetime >= {$tempThisMonth}
							AND queuetime < {$tempNextMonth}
							AND statid <> 0

					UNION

					SELECT	credit_used
					FROM	[|PREFIX|]user_credit_summary
					WHERE	userid = {$userobject->userid}
							AND startperiod = {$tempStartPeriod}
				";

				$tempResult = $db->Query($tempQuery);
				if (!$tempResult) {
					trigger_error(__CLASS__ . '::' . __METHOD__ . ' -- Cannot query database: ' . $db->Error(), E_USER_NOTICE);
					return false;
				}

				while ($tempRow = $db->Fetch($tempResult)) {
					$credits[] = $tempRow['credit_used'];
				}

				$db->FreeResult($tempResult);
			// -----


			// If no credits have been used this month, return permonth
			if (empty($credits)) {
				return $userobject->permonth;
			}

			$tempSum = array_sum($credits);
			$tempCreditLeft = $userobject->permonth - $tempSum;

			if (!$percentage) {
				return $tempCreditLeft;
			}

			return ($tempCreditLeft / $userobject->permonth * 100);
		}

		/**
		 * Get available fixed credit
		 *
		 * @param record_Users|integer $user User record object or user ID
		 * @return integer|boolean Returns TRUE if user has unlimited credit, an integer if user has a limit, FALSE if it encountered any error
		 */
		static public function creditAvailableFixed($user)
		{
			$db = IEM::getDatabase();
			$userobject = null;


			// ----- PRE
				if ($user instanceof record_Users) {
					$userobject = $user;
				} else {
					$userobject = self::getRecordByID($user);
				}

				if (empty($userobject)) {
					trigger_error(__CLASS__ . '::' . __METHOD__ . ' -- User is not specified', E_USER_NOTICE);
					return false;
				}

				// No limit, returns TRUE
				if ($userobject->unlimitedmaxemails == 1) {
					return true;
				}
			// -----

			return $userobject->maxemails;
		}

		/**
		 * Get total available credit
		 *
		 * @param record_Users|integer $user User record object or user ID
		 * @return integer|boolean Returns TRUE if user has unlimited credit, an integer if user has a limit, FALSE if it encountered any error
		 *
		 * @todo all
		 */
		static public function creditAvailableTotal($user)
		{
			$db = IEM::getDatabase();
			$userobject = null;


			// ----- PRE
				if ($user instanceof record_Users) {
					$userobject = $user;
				} else {
					$userobject = self::getRecordByID($user);
				}

				if (empty($userobject)) {
					trigger_error(__CLASS__ . '::' . __METHOD__ . ' -- User is not specified', E_USER_NOTICE);
					return false;
				}
			// -----

			$fixed = self::creditAvailableFixed($userobject);
			$monthly = self::creditAvailableThisMonth($userobject);

			// If either functions return FALSE, propagate it.
			if ($fixed === false || $monthly === false) {
				return false;
			}

			if ($fixed === true) {
				return $monthly;
			} elseif ($monthly === true) {
				return $fixed;
			}

			return $fixed + $monthly;
		}

		/**
		 * Evaluate credit warning conditions
		 *
		 * This method will evaluate credit warnings for a particular user.
		 * It will dispatch warning emails accrodingly.
		 *
		 * @param record_Users|integer $user User record object or user ID
		 * @return boolean Returns TRUE if successful, FALSE otherwise
		 *
		 * @todo fixed credits does not have warnings yet
		 */
		static public function creditEvaluateWarnings($user)
		{
			$userobject = null;
			$warnings = null;
			$this_month = mktime(0, 0, 0, date('n'), 1, date('Y'));
			$credit_left = null;


			// ----- PRE
				if ($user instanceof record_Users) {
					$userobject = $user;
				} else {
					$userobject = self::getRecordByID($user);
				}

				if (empty($userobject)) {
					trigger_error(__CLASS__ . '::' . __METHOD__ . ' -- User is not specified', E_USER_NOTICE);
					return false;
				}
			// -----

			// Credit warnings are not enabled
			if (!SENDSTUDIO_CREDIT_WARNINGS) {
				return true;
			}

			$credit_left = self::creditAvailableThisMonth($userobject, true);

			// If warning has been sent out (this month), do not continue:
			// - credit_warning_percentage is smaller than $credit_left
			if ($userobject->credit_warning_time >= $this_month && $userobject->credit_warning_percentage <= $credit_left) {
				return true;
			}

			require_once(IEM_PUBLIC_PATH . '/functions/api/settings.php');
			$tempSettingsAPI = new Settings_API();
			$warnings = $tempSettingsAPI->GetCreditWarningsSettings();

			// Does not hany any warnings setup? Well... we can't continue then.
			if (empty($warnings)) {
				return false;
			}

			$whichlevel = false;
			foreach ($warnings as $warning) {
				// If credit level is smaller than credit_left, continue
				if ($warning['creditlevel'] < $credit_left) {
					continue;
				}

				// Only take the smallest value
				if ($whichlevel !== false && $whichlevel < $warning['creditlevel']) {
					continue;
				}

				// If the warning is not enabled, continue
				if (!$warning['enabled']) {
					continue;
				}

				// Because we only evaluate "monthly warnings", we skip any fix credit warnings
				if (!$warning['aspercentage']) {
					continue;
				}

				// Skip any warnings that have been sent out this month
				if ($userobject->credit_warning_time >= $this_month && $warning['creditlevel'] >= $userobject->credit_warning_percentage) {
					continue;
				}

				$whichlevel = $warning;
			}

			if ($whichlevel) {
				$tempNames = explode(' ', $userobject->fullname);
				$tempLastName = array_pop($tempNames);
				$tempFirstName = implode(' ', $tempNames);

				$available_custom_fields_key = array(
					'%%user_fullname%%',
					'%%user_firstname%%',
					'%%user_lastname%%',
					'%%credit_total%%',
					'%%credit_remains%%',
					'%%credit_remains_precentage%%',
					'%%credit_used%%',
					'%%credit_used_percentage%%'
				);

				$available_custom_fields_value = array(
					$userobject->fullname,
					$tempFirstName,
					$tempLastName,
					$userobject->permonth,
					intval($userobject->permonth * ($credit_left / 100)),
					intval($credit_left),
					intval($userobject->permonth * ((100 - $credit_left) / 100)),
					intval(100 - $credit_left)
				);

				$email_contents = str_replace($available_custom_fields_key, $available_custom_fields_value, $whichlevel['emailcontents']);
				$email_subject = str_replace($available_custom_fields_key, $available_custom_fields_value, $whichlevel['emailsubject']);

				// ----- We found which warnings it is that we want to send out
					require_once(IEM_PATH . '/ext/interspire_email/email.php');
					$emailapi = new Email_API();
					$emailapi->SetSmtp(SENDSTUDIO_SMTP_SERVER, SENDSTUDIO_SMTP_USERNAME, @base64_decode(SENDSTUDIO_SMTP_PASSWORD), SENDSTUDIO_SMTP_PORT);
					if ($userobject->smtpserver) {
						$emailapi->SetSmtp($userobject->smtpserver, $userobject->smtpusername, $userobject->smtppassword, $userobject->smtpport);
					}
					$emailapi->ClearRecipients();
					$emailapi->ForgetEmail();
					$emailapi->Set('forcechecks', false);
					$emailapi->AddRecipient($userobject->emailaddress, $userobject->fullname, 't');
					$emailapi->Set('FromName', false);
					$emailapi->Set('FromAddress', (defined('SENDSTUDIO_EMAIL_ADDRESS') ? SENDSTUDIO_EMAIL_ADDRESS : $userobject->emailaddress));
					$emailapi->Set('BounceAddress', SENDSTUDIO_EMAIL_ADDRESS);
					$emailapi->Set('CharSet', SENDSTUDIO_CHARSET);
					$emailapi->Set('Subject', $email_subject);
					$emailapi->AddBody('text', $email_contents);
					$status = $emailapi->Send();
					if ($status['success'] != 1) {
						trigger_error(__CLASS__ . '::' . __METHOD__ . ' -- Was not able to send email: ' . serialize($status['failed']), E_USER_NOTICE);
						return false;
					}
				// -----

				// ----- Update user record
					$db = IEM::getDatabase();
					$status = $db->Query("UPDATE [|PREFIX|]users SET credit_warning_time = {$this_month}, credit_warning_percentage = {$whichlevel['creditlevel']} WHERE userid = {$userobject->userid}");

					// Update user object in session
					// FIXME, we really need to make a special getter/setter for this
					$current_user = IEM::getCurrentUser();
					if ($current_user && $current_user->userid == $userobject->userid) {
						$current_user->credit_warning_time = $this_month;
						$current_user->credit_warning_percentage = $whichlevel['creditlevel'];
					}
				// -----
			}

			return true;
		}
	// --------------------------------------------------------------------------------
}
