<?php

namespace Vend\Vacation\Error;

use JMS\Serializer\Annotation as Serializer;

/**
 * @Serializer\ExclusionPolicy("all")
 */
abstract class AbstractError
{
    /**
     * @var int
     */
    protected $code;

    /**
     * @var string
     * @Serializer\Expose()
     * @Serializer\Accessor("getMessage")
     */
    protected $message;

    /**
     * @var \Exception
     */
    protected $exception;

    public function __construct($message, $code, $exception = null)
    {
        $this->message   = $message;
        $this->code      = $code;
        $this->exception = $exception;
    }

    /**
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        $debug = true;

        if ($debug && $this->exception) {
            return $this->exception->getMessage();
        } else {
            return $this->message;
        }
    }
}
