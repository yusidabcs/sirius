<?php
require_once 'main.php';
require_once 'vendor/autoload.php';
// Create a new scheduler
$scheduler = NS_APP_CLASSES.'\\scheduler\\scheduler';
$scheduler = new $scheduler;
// ... configure the scheduled jobs (see below) ...

$mailing_db = new \core\modules\send_email\models\common\db;
$reminders = $mailing_db->getActiveReminder();

$scheduler->call(function () {
    $db = new \core\modules\workflow\scheduler();
    $db->runRecruitmentTrackerGroup();
})->daily();

$scheduler->call(function () {
    $db = new \core\modules\workflow\scheduler();
    $db->runInterviewTrackerGroup();
})->daily();

$scheduler->call(function () {
    $db = new \core\modules\workflow\scheduler();
    $db->runDeploymentTrackerGroup();
})->daily();

$scheduler->call(function () {
    $db = new \core\modules\workflow\scheduler();
    $db->runOtherTrackerGroup();
})->daily();

$scheduler->call(function () {
    $db = new \core\modules\send_email\scheduler_campaign();
});

if ($reminders) {
    # code...
    foreach ($reminders as $key => $reminder) {
        # code...
        switch ($reminder['cron_timing']) {
            case 'daily':
                $scheduler->call(function () use($mailing_db, $reminder) {
                    $db = new \core\modules\send_email\scheduler($reminder['campaign_id']);
                    $mailing_db->updateReminderTimestamp($reminder['reminder_id']);
                })->daily();
                break;
            case 'every_2_days':
                $scheduler->call(function () use($mailing_db, $reminder) {
                    $db = new \core\modules\send_email\scheduler($reminder['campaign_id']);
                    $mailing_db->updateReminderTimestamp($reminder['reminder_id']);
                })->at("0 23 */2 * *");
                break;
            case 'every_3_days':
                $scheduler->call(function () use($mailing_db, $reminder) {
                    $db = new \core\modules\send_email\scheduler($reminder['campaign_id']);
                    $mailing_db->updateReminderTimestamp($reminder['reminder_id']);
                })->at("0 23 */3 * *");
                break;
            case 'weekly':
                $scheduler->call(function () use($mailing_db, $reminder) {
                    $db = new \core\modules\send_email\scheduler($reminder['campaign_id']);
                    $mailing_db->updateReminderTimestamp($reminder['reminder_id']);
                })->weekly();
                break;
            
            default:
                echo "Unsupported operation!!";
                break;
        }
    }
}

$scheduler->call(function () {
    $db = new \core\modules\deployment\scheduler();
})->daily();

// interview scheduler
$scheduler->call(function () {
    $db = new \core\modules\interview\scheduler();
})->daily();
// Let the scheduler execute jobs which are due.
$scheduler->run();