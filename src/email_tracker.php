<?php
require_once 'main.php';
	
$tracker_code = $_GET['t'];

if( strlen($tracker_code) != 32 ) 
{
    die('That just does not do it for me!');
}

$db = new \core\modules\send_email\models\common\scheduler_db;

$tracker = $db->getTrackerEmail($tracker_code);
if($tracker){
    $tracker = $db->updateTrackerEmail($tracker_code,'opened');
}else{
    die('Tracker not found!');
}
