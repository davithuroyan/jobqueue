<?php
/**
 * Created by PhpStorm.
 * User: davit
 * Date: 15-Aug-18
 * Time: 11:47 PM
 */

namespace app;

class Worker
{
    protected $queue;

    /**
     * @param Queue $queue
     * @return $this
     */
    public function setQueue(Queue $queue)
    {
        $this->queue = $queue;
        return $this;
    }

    /**
     * @return mixed
     */
    public function run()
    {

        $job = $this->queue->getJob();
        if ($job) {

            $httpCode = $job->execute();
            $job->setResponseCode($httpCode);

            if ($httpCode == 200) {

                $job->setStatus(Job::STATUS_DONE);
            } else {
                $job->setStatus(Job::STATUS_FAILED);
            }
            $job->setExecutedAt(date("Y-m-d"));

            $this->queue->updateJob($job);

            return $job;
        }
    }
}