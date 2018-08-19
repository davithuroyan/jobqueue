<?php
/**
 * Created by PhpStorm.
 * User: davit
 * Date: 15-Aug-18
 * Time: 11:47 PM
 */

namespace app;

class Job implements JobInterface
{

    const STATUS_TIMEOUT = -1;
    const STATUS_FAILED = 0;
    const STATUS_NEW = 1;
    const STATUS_EXECUTING = 2;
    const STATUS_DONE = 3;

    const PRIORITY_LOW = 1;
    const PRIORITY_MEDIUM = 2;
    const PRIORITY_HIGH = 3;

    protected $id;
    protected $url;
    protected $responseCode;
    protected $executedAt;
    protected $status;
    protected $priority;


    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @param string $url
     */
    public function setUrl(string $url)
    {
        $this->url = $url;
    }

    /**
     * @param int $code
     */
    public function setResponseCode(int $code)
    {
        $this->responseCode = $code;
    }

    /**
     * @param int $status
     */
    public function setStatus(int $status)
    {
        $this->status = $status;
    }

    /**
     * @param $priority
     */
    public function setPriority(int $priority)
    {
        $this->priority = $priority;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return int
     */
    public function getResponseCode()
    {
        return $this->responseCode;
    }


    /**
     *
     * @return int
     */
    public function getPriority()
    {
        return $this->priority;
    }


    /**
     * @param $executedAt
     * @return Job
     */
    public function setExecutedAt($executedAt)
    {
        $this->executedAt = $executedAt;
        return $this;
    }

    /**
     * @return string
     */
    public function getExecutedAt()
    {

        return $this->executedAt;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return int
     */
    public function execute()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_HEADER, true);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return $httpCode;
    }

    /**
     * @param \stdClass $data
     * @return Job
     */
    public static function populate(\stdClass $data)
    {
        $obj = new Job();
        $obj->setId($data->id);
        $obj->setUrl($data->url);
        $obj->setPriority($data->priority);
        $obj->setResponseCode($data->response_code);
        $obj->setExecutedAt($data->executed_at);
        $obj->setStatus($data->status);

        return $obj;
    }

}