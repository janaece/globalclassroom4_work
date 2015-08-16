<?php

/**
 * Description of GcrBackgroundProcessType:
 * 
 *
 * @author Ron Stewart
 */
abstract class GcrBackgroundProcessType 
{
    protected $process;
    
    public function __construct($process)
    {
        $this->process = $process;
        $this->initialize();
    }
    abstract protected function initialize();
    abstract public function startProcess();
}

?>
