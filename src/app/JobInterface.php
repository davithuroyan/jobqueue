<?php

namespace app;

interface JobInterface
{
    /**
     * Function that will execute job
     *
     * @return mixed
     */
    public function execute();
}


?>