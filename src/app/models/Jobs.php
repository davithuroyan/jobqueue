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

    public function create(Job $job)
    {

        $data = [
            'url' => $job->getUrl(),
            'status' => $job->getStatus(),
            'priority' => $job->getPriority()
        ];

        return $this->_db->insert(self::TABLE, $data);
    }

    public function get($priority = null)
    {
        $res = $this->_db->select(self::TABLE, ['*'], ["status" => Job::STATUS_NEW], "priority", "DESC");
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