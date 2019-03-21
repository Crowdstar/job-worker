<?php

namespace CrowdStar\JobWorker;

/**
 * Class Job
 *
 * Unique Job class
 */
class UniqueJob extends Job
{
    
    private $_unique_job_key;

    public function setUp()
    {
        list($this->_callback, $this->_params, $this->_bootstrap_file_path, $this->_unique_job_key) = $this->_unserialize($this->args[0]);
        $unique_job_key = $this->_unique_job_key;
        register_shutdown_function(
            function () use ($unique_job_key) {
                \Resque::redis()->del($unique_job_key);
            }
        );
    }

    /**
     * @param $workload
     *
     * @return array
     */
    private function _unserialize($workload)
    {
        $array = unserialize($workload);
        return array($array['callback'], $array['params'], $array['bootstrap_file_path'], $array['unique_job_key']);
    }
    

}