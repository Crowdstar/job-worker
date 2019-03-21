<?php

namespace CrowdStar\JobWorker;

/**
 * @package CrowdStar
 */
abstract class AbstractQueue
{

    const UNIQUE_JOB_LOCK_TIMEOUT = 86400;

    protected $_bootstrap_file_path = null;

    /**
     * @param $queue
     * @param $callback
     * @param $params
     * @param $track_status
     *
     * @return string
     */
    abstract public function addJob($queue, $callback, $params, $track_status = false);

    /**
     * @param $queue
     * @param $callback
     * @param $unique_job_key
     * @param $params
     * @param $track_status
     *
     * @return bool|string
     */
    abstract public function addUniqueJob($queue, $callback, $unique_job_key, $params, $track_status = false);

    /**
     * Add a high-priority job to the queue
     *
     * @param array   $callback     array('Class', 'function')  // for static functions in a class
     *                              array($this, '_getAndCacheData') // do allow member methods of
     *                              object instances // do not allow callbacks on objects -- can't
     *                              serialize them
     * @param array   $params       Parameters for the job.  Must be serializable.
     * @param boolean $track_status Set to true to be able to monitor the status of a job.
     *
     * @return string  ID of the job
     */
    public function addHighPriorityJob($callback, $params = array(), $track_status = false)
    {
        return $this->addJob('high', $callback, $params, $track_status);
    }

    /**
     * Add a low-priority job to the queue
     *
     * @param array   $callback     array('Class', 'function')  // for static functions in a class
     *                              array($this, '_getAndCacheData') // do allow member methods of
     *                              object instances // do not allow callbacks on objects -- can't
     *                              serialize them
     * @param array   $params       Parameters for the job.  Must be serializable.
     * @param boolean $track_status Set to true to be able to monitor the status of a job.
     *
     * @return string  ID of the job
     */
    public function addLowPriorityJob($callback, $params = array(), $track_status = false)
    {
        return $this->addJob('low', $callback, $params, $track_status);
    }

    /**
     * Add a high-priority unique job to the queue
     *
     * @param array   $callback       array('Class', 'function')  // for static functions in a class
     *                                array($this, '_getAndCacheData') // do allow member methods of
     *                                object instances // do not allow callbacks on objects -- can't
     *                                serialize them
     * @param string  $unique_job_key Unique job key
     * @param array   $params         Parameters for the job.  Must be serializable.
     * @param boolean $track_status   Set to true to be able to monitor the status of a job.
     *
     * @return string  ID of the job
     */
    public function addHighPriorityUniqueJob($callback, $unique_job_key, $params = array(), $track_status = false)
    {
        return $this->addUniqueJob('high', $callback, $unique_job_key, $params, $track_status);
    }

    /**
     * Add a low-priority unique job to the queue
     *
     * @param array   $callback       array('Class', 'function')  // for static functions in a class
     *                                array($this, '_getAndCacheData') // do allow member methods of
     *                                object instances // do not allow callbacks on objects -- can't
     *                                serialize them
     * @param string  $unique_job_key Unique job key
     * @param array   $params         Parameters for the job.  Must be serializable.
     * @param boolean $track_status   Set to true to be able to monitor the status of a job.
     *
     * @return string  ID of the job
     */
    public function addLowPriorityUniqueJob($callback, $unique_job_key, $params = array(), $track_status = false)
    {
        return $this->addUniqueJob('low', $callback, $unique_job_key, $params, $track_status);
    }

    /**
     * Add the path to a bootstrap file to be included before running the job
     *
     * @param $file_path
     */
    public function addBootstrapFilePath($file_path)
    {
        if (!file_exists($file_path)) {
            throw new \InvalidArgumentException;
        }

        $this->_bootstrap_file_path = $file_path;
    }

    /**
     * @param $callback
     * @param $params
     * @param $bootstrap_file_path
     * @param $unique_job_key
     *
     * @return string
     */
    protected function _serialize($callback, $params, $bootstrap_file_path, $unique_job_key=null)
    {
        if (isset($unique_job_key)) {
            return serialize(compact('callback', 'params', 'bootstrap_file_path', 'unique_job_key'));
        }
        return serialize(compact('callback', 'params', 'bootstrap_file_path'));
    }

    protected function createUniqueKey($queue, $key)
    {
        return 'unique:queue:'.$queue.':job:'.$key;
    }

    /**
     * @return int
     */
    abstract public function getQueueSize($queue);

    /**
     * @return int
     */
    public function getHighPriorityQueueSize()
    {
        return $this->getQueueSize("high");
    }

    /**
     * @return int
     */
    public function getLowPriorityQueueSize()
    {
        return $this->getQueueSize("low");
    }
}

