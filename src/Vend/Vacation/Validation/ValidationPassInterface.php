<?php

namespace Vend\Vacation\Validation;

use Vend\Vacation\Metadata\Operation;
use Vend\Vacation\Operation\ArgumentBag;
use Vend\Vacation\Request\RequestInterface;

interface ValidationPassInterface
{
    /**
     * @param RequestInterface $request
     * @param object           $controller
     * @param Operation        $operationMetadata
     * @param ArgumentBag      $operationArguments
     */
    public function validate(RequestInterface $request, $controller, Operation $operationMetadata, ArgumentBag $operationArguments);
}
