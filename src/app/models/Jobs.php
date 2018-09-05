<?php
/**
 * Created by PhpStorm.
 * User: davit
 * Date: 16-Aug-18
 * Time: 12:06 AM
 */

namespace app\models;


use app\Job;
use app\library\DB;

class Jobs implements MainInterface
{
    const TABLE = 'jobs';

    protected $_db;

    public function __construct(DB $db)
    {
//        $db = new DB();
        $this->_db = $db;
    }

    public function setOptions(array $options)
    {
        $this->options = $options;
        return $this;
    }

    /**
     * @param Job $job
     * @return bool
     */
    public function create(Job $job)
    {

        $data = [
            'url' => $job->getUrl(),
            'status' => $job->getStatus(),
            'priority' => $job->getPriority()
        ];

        return $this->_db->insert(self::TABLE, $data);
    }

    /**
     * @return Job|null
     */
    public function get()
    {
        $query = "SET @update_id := 0;
            UPDATE jobs SET status = " . Job::STATUS_EXECUTING . ", id = (SELECT @update_id := id)
            WHERE status = " . Job::STATUS_NEW . " ORDER BY priority DESC LIMIT 1;";

        $jobId = $this->_db->execute($query);

        $res = $this->_db->select(self::TABLE, ['*'], ["id" => $jobId]);
        if ($res) {
            return Job::populate($res);
        }
        return null;

    }

    public function update(Job $job)
    {
        $data = [
            'executed_at' => $job->getExecutedAt(),
            'status' => $job->getStatus(),
            'response_code' => $job->getResponseCode()
        ];

        return $this->_db->update(self::TABLE, $job->getId(), $data);
    }


}