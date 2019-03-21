<?php

namespace CrowdStar\JobWorker;

/**
 * Class Job
 *
 * Unique Job class
 */
class UniqueJob extends Job
{
    
    private $unique_job_key;

    public function setUp()
    {
        list(
            $this->callback,
            $this->params,
            $this->bootstrap_file_path,
            $this->unique_job_key
        ) = $this->unserialize($this->args[0]);

        $unique_job_key = $this->unique_job_key;
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
    private function unserialize($workload)
    {
        $array = unserialize($workload);
        return array($array['callback'], $array['params'], $array['bootstrap_file_path'], $array['unique_job_key']);
    }
}
