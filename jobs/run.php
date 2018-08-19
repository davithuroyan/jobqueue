<?php


require_once __DIR__ . "/../src/autoload.php";

use app\Worker;

$db = new \app\library\DB();

$jobModel = new \app\models\Jobs($db);
$queue = new \app\Queue($jobModel);

$worker = new Worker();
$worker->setQueue($queue);

while ($job = $worker->run()) {
    try {
        echo "Executed Job No" . $job->getId() . PHP_EOL;
    } catch (Exception $ex) {
        echo 'Error: ' . $e->getMessage() . PHP_EOL;
    }
}

