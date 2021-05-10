<?php namespace core\app\classes\scheduler;

use Exception;

class failedjob
{
    /**
     * @var job
     */
    private $job;

    /**
     * @var Exception
     */
    private $exception;

    public function __construct(job $job, Exception $exception)
    {
        $this->job = $job;
        $this->exception = $exception;
    }

    public function getJob(): job
    {
        return $this->job;
    }

    public function getException(): Exception
    {
        return $this->exception;
    }
}