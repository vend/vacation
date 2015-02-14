<?php

namespace Vend\Vacation\Request\Adaptor;

use Vend\Vacation\Request\RequestInterface;

class DefaultAdapter implements RequestInterface
{
    /**
     * @var \ArrayObject
     */
    private $attributes;

    /**
     * @var \ArrayObject
     */
    private $headers;

    public function __construct()
    {
        $this->attributes = new \ArrayObject();
        $this->parseHeaders();
    }

    protected function parseHeaders()
    {
        $this->headers = new \ArrayObject();

        foreach ($_SERVER as $key => $value) {
            if (0 !== strpos($key, 'HTTP_')) {
                continue;
            }

            $this->headers[substr($key, 5)] = $value;
        }
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return sprintf(
            'http%s://%s%s',
            isset($_SERVER['HTTPS']) ? 's' : '',
            $_SERVER['HTTP_HOST'],
            $_SERVER['REQUEST_URI']
        );
    }

    public function setAttribute($attribute, $value)
    {
        $this->attributes[$attribute] = $value;
    }

    /**
     * @param string $attribute
     * @return mixed
     */
    public function getAttribute($attribute)
    {
        return $this->attributes->offsetExists($attribute) ? $this->attributes[$attribute] : null;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * @return array
     */
    public function getQueryParameters()
    {
        return $_GET;
    }

    /**
     * @return array
     */
    public function getPayloadAsArray()
    {
        // TODO: Implement getPayloadAsArray() method.
        return [];
    }

    /**
     * @param string $header
     * @param mixed  $default
     * @return mixed
     */
    public function getHeader($header, $default = null)
    {
        return $this->headers->offsetExists($header) ? $this->headers[$header] : $default;
    }
}
