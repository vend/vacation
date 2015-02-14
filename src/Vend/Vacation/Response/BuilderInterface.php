<?php

namespace Vend\Vacation\Response;

use Vend\Vacation\Request\RequestInterface;

interface BuilderInterface
{
    /**
     * @param RequestInterface $request
     * @param mixed            $content
     * @return ResponseInterface
     */
    public function create(RequestInterface $request, $content);
}
