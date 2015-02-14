<?php

namespace Vend\Vacation\Annotation;

/**
 * @Annotation
 */
class Operation
{
    /**
     * @var string
     */
    private $requestMethod;

    /**
     * @var string
     */
    private $endpoint;

    /**
     * @var array
     */
    private $parameters = [];

    /**
     * @param array $values
     */
    public function __construct(array $values = [])
    {
        $this->requestMethod = $values['value'];

        if (empty($values['endpoint'])) {
            $values['endpoint'] = 'default';
        }

        $this->endpoint = $values['endpoint'];

        if (!empty($values['parameters'])) {
            $this->parameters = $values['parameters'];
        }
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @return string
     */
    public function getRequestMethod()
    {
        return $this->requestMethod;
    }

    /**
     * @return string
     */
    public function getEndpoint()
    {
        return $this->endpoint;
    }
}
