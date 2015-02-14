<?php

namespace Vend\Vacation\Request;

use Vend\Vacation\Metadata;

class Context
{
    /**
     * @var object
     */
    private $controller;

    /**
     * @var Metadata\Controller
     */
    private $controllerMetadata;

    /**
     * @var Metadata\Endpoint
     */
    private $endpointMetadata;

    /**
     * @var Metadata\Operation
     */
    private $operationMetadata;

    /**
     * @return object
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * @param object $controller
     */
    public function setController($controller)
    {
        $this->controller = $controller;
    }

    /**
     * @return Metadata\Controller
     */
    public function getControllerMetadata()
    {
        return $this->controllerMetadata;
    }

    /**
     * @param Metadata\Controller $controllerMetadata
     */
    public function setControllerMetadata($controllerMetadata)
    {
        $this->controllerMetadata = $controllerMetadata;
    }

    /**
     * @return Metadata\Endpoint
     */
    public function getEndpointMetadata()
    {
        return $this->endpointMetadata;
    }

    /**
     * @param Metadata\Endpoint $endpointMetadata
     */
    public function setEndpointMetadata($endpointMetadata)
    {
        $this->endpointMetadata = $endpointMetadata;
    }

    /**
     * @return Metadata\Operation
     */
    public function getOperationMetadata()
    {
        return $this->operationMetadata;
    }

    /**
     * @param Metadata\Operation $operationMetadata
     */
    public function setOperationMetadata($operationMetadata)
    {
        $this->operationMetadata = $operationMetadata;
    }
}
