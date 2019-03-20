<?php

namespace CrowdStar\JobWorker;

use ResqueScheduler\ResqueScheduler;

/**
 * Wrapper for job workers
 *
 * Currently built for PHP Resque Ex
 *
 * @package CrowdStar
 */
class Queue extends AbstractQueue
{
    /**
     * Queue constructor.
     *
     * @param string $server
     * @param string $password
     */
    public function __construct($server, $password = null) {
        \Resque::setBackend($server, 0, 'resque', $password);
    }

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
        return \Resque::enqueue(
            $queue,
            'CrowdStar\JobWorker\Job',
            array(
                $this->_serialize($callback, $params, $this->_bootstrap_file_path)
            ),
            $track_status
        );
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
        $unique_job_key = $this->createUniqueKey($queue, $unique_job_key);

        if (\Resque::redis()->exists($unique_job_key)) {
            return false;
        }

        \Resque::redis()->set($unique_job_key, 1, self::UNIQUE_JOB_LOCK_TIMEOUT);

        return \Resque::enqueue(
            $queue,
            'CrowdStar\JobWorker\UniqueJob',
            array(
                $this->_serialize($callback, $params, $this->_bootstrap_file_path, $unique_job_key)
            ),
            $track_status
        );
    }

    /**
     * @param $queue
     * @param $at
     * @param $callback
     * @param $params
     * @param $track_status
     * @return mixed|string
     */
    public function scheduleJob($queue, $at, $callback, $params, $track_status) {
        return ResqueScheduler::enqueueAt(
            $at,
            $queue,
            'CrowdStar\JobWorker\Job',
            array(
                $this->_serialize($callback, $params, $this->_bootstrap_file_path)
            ),
            $track_status
        );
    }

    /**
     * Returns the size of a given queue
     * @param $queue
     * @return int
     */
    public function getQueueSize($queue){
        return \Resque::size($queue);
    }
}

