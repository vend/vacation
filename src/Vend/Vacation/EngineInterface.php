<?php

namespace Vend\Vacation;

use Vend\Vacation\Request\RequestInterface;

interface EngineInterface
{
    /**
     * Convert a request into a response.
     *
     * @param RequestInterface $request
     * @return object
     */
    public function execute(RequestInterface $request);
}
