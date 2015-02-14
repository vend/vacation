<?php

namespace Vend\Vacation\Request;

interface ResolverInterface
{
    /**
     * @param RequestInterface $request
     * @return Context
     */
    public function resolve(RequestInterface $request);
}
