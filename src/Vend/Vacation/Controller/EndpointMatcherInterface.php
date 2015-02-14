<?php

namespace Vend\Vacation\Controller;

use Vend\Vacation\Metadata;
use Vend\Vacation\Request\RequestInterface;

interface EndpointMatcherInterface
{
    /**
     * @param RequestInterface  $request
     * @param Metadata\Endpoint $endpointMetadata
     * @return boolean
     */
    public function matches(RequestInterface $request, Metadata\Endpoint $endpointMetadata);
}
