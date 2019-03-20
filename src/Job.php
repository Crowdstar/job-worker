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

    protected $_callback;
    protected $_params;
    protected $_bootstrap_file_path;

    public function setUp()
    {
        list($this->_callback, $this->_params, $this->_bootstrap_file_path) = $this->_unserialize($this->args[0]);
    }

    public function perform() {
        if (!is_null($this->_bootstrap_file_path)) {
            include $this->_bootstrap_file_path;
        }

        call_user_func_array($this->_callback, $this->_params);
    }

    /**
     * @param $workload
     *
     * @return array
     */
    private function _unserialize($workload)
    {
        $array = unserialize($workload);
        return array($array['callback'], $array['params'], $array['bootstrap_file_path']);
    }
}