<?php
/**
 * Created by PhpStorm.
 * User: davit
 * Date: 18-Aug-18
 * Time: 2:33 PM
 */

require_once __DIR__ . "/../src/autoload.php";

use app\Job;

$db = new \app\library\DB();
//$connection = $db->dbConnect();
$jobModel = new \app\models\Jobs($db);
$queue = new \app\Queue($jobModel);

$job1 = new Job();
$job1->setUrl("http://localhost/test/");
$job1->setPriority(Job::PRIORITY_HIGH);
$job1->setStatus(Job::STATUS_NEW);
$queue->createJob($job1);

$job2 = new Job();
$job2->setUrl("http://localhost/test/");
$job2->setPriority(Job::PRIORITY_MEDIUM);
$job2->setStatus(Job::STATUS_NEW);

$queue->createJob($job2);