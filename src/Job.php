<?php

namespace CrowdStar\JobWorker;

/**
 * Class Job
 *
 * Generic Job class
 */
class Job
{
    /**
     * @var array $args
     *
     * Contains the serialized workload as the single element of the array
     */
    public $args;

    protected $callback;
    protected $params;
    protected $bootstrap_file_path;

    public function setUp()
    {
        list($this->callback, $this->params, $this->bootstrap_file_path) = $this->unserialize($this->args[0]);
    }

    public function perform()
    {
        if (!is_null($this->bootstrap_file_path)) {
            include $this->bootstrap_file_path;
        }

        call_user_func_array($this->callback, $this->params);
    }

    /**
     * @param $workload
     *
     * @return array
     */
    private function unserialize($workload)
    {
        $array = unserialize($workload);
        return array($array['callback'], $array['params'], $array['bootstrap_file_path']);
    }
}
