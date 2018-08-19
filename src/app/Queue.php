<?php
/**
 * Created by PhpStorm.
 * User: davit
 * Date: 15-Aug-18
 * Time: 11:52 PM
 */

namespace app;


use app\models\MainInterface;

class Queue
{

    protected $model;

    /**
     * Queue constructor.
     * @param MainInterface $model
     */
    public function __construct(MainInterface $model)
    {
        $this->model = $model;
    }

    /**
     * @param JobInterface $job
     * @return mixed
     */
    public function createJob(JobInterface $job)
    {
        return $this->getModel()->create($job);
    }

    /**
     *
     * @param int $priority
     *
     * @return array
     */
    public function getJob($priority = null)
    {
        $job = $this->getModel()->get($priority);
        if ($job) {
            $job->setStatus(Job::STATUS_EXECUTING);
            $this->getModel()->update($job);
            return $job;
        }
        return null;
    }

    /**
     * @param JobInterface $job
     * @return mixed
     */
    public function updateJob(JobInterface $job)
    {
        return $this->getModel()->update($job);
    }


    /**
     * @return MainInterface
     */
    public function getModel()
    {
        return $this->model;
    }
}