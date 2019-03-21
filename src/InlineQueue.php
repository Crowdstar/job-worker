<?php

namespace CrowdStar\JobWorker;

/**
 * Run jobs inline, without a Queue
 *
 * @package CrowdStar
 */
class InlineQueue extends AbstractQueue
{
    
    /**
     * @param $queue
     * @param $callback
     * @param $params
     * @param $track_status
     *
     * @return string
     */
    public function addJob($queue, $callback, $params, $track_status = false)
    {
        return call_user_func_array($callback, $params);
    }

    /**
     * @param $queue
     * @param $callback
     * @param $unique_job_key
     * @param $params
     * @param $track_status
     *
     * @return bool|string
     */
    public function addUniqueJob($queue, $callback, $unique_job_key, $params, $track_status = false)
    {
        $this->addJob($queue, $callback, $params, $track_status);
    }

    /**
     * @param  $queue
     * @param  $at
     * @param  $callback
     * @param  $params
     * @param  $track_status
     * @return void
     * @throws \Exception
     */
    public function scheduleJob($queue, $at, $callback, $params, $track_status)
    {
        throw new \Exception("Job scheduling is not supported in inline queue");
    }

    /**
     * @return int
     */
    public function getQueueSize($queue)
    {
        return 0;
    }
}
