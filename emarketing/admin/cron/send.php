<?php
/**
* This file handles scheduled sending by itself. It's main task is to set up the required information (classes), load up the database and so on, then pass control over to the jobs_send.php file which runs everything from there.
* This can be run as a separate cron job to the jobs.php file to allow more specific / frequent scheduling of just scheduled sending.
*
* @package interspire.iem.cron
*/

// Include CRON common file
require_once dirname(__FILE__) . '/common.php';

/**
* Make sure we update how often cron is running.
*/
require_once(SENDSTUDIO_API_DIRECTORY . '/jobs.php');
$jobs_api = new Jobs_API(true);

$allow_send = CheckCronSchedule('send');

if ($allow_send) {
	/**
	* Include the job api. This will let us fetch jobs from the queue and start them up.
	*/
	require_once(SENDSTUDIO_API_DIRECTORY . '/jobs_send.php');

	/**
	* Set up the Send Jobs API.
	*/
	$JobsAPI = new Jobs_Send_API();

	/**
	* Check if there are any jobs in progress that aren't stale.
	* If there is a global sending rate in place we don't want to overlap jobs.
	*/
	if ($JobsAPI->ShouldLockSending() && $JobsAPI->JobsRunning('newsletter')) {
		return;
	}

	/**
	* While we keep fetching a job, process it.
	*
	* @see FetchJob
	* @see ProcessJob
	*/
	while ($job = $JobsAPI->FetchJob('send')) {
		$result = $JobsAPI->ProcessJob($job);
		if (!$result) {
			echo "*** WARNING *** send job '" . $job . "' couldn't be processed.\n";
			break;
		}
	}
}

?>
