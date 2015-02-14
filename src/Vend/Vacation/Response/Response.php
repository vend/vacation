<?php

namespace Vend\Vacation\Response;

class Response implements ResponseInterface
{
    /**
     * @var \ArrayObject
     */
    private $headers;

    /**
     * @var int
     */
    private $statusCode = ResponseInterface::STATUS_OK;

    /**
     * @var string|null
     */
    private $content;

    /**
     * @param string $name
     * @param mixed  $value
     */
    public function setHeader($name, $value)
    {
        $this->headers[$name] = $value;
    }

    /**
     * @return \ArrayObject
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @param int $statusCode
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = (int)$statusCode;
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return string|null
     */
    public function getContent()
    {
        return $this->content;
    }
}
