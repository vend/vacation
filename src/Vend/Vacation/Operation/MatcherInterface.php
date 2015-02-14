<?php

namespace Vend\Vacation\Operation;

use Vend\Vacation\Metadata\Operation;
use Vend\Vacation\Request\RequestInterface;

interface MatcherInterface
{
    /**
     * @param RequestInterface $request
     * @param Operation        $operationMetadata
     * @return mixed
     */
    public function matches(RequestInterface $request, Operation $operationMetadata);
}
