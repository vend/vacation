<?php

namespace Vend\Vacation\Processor;

use Vend\Vacation\Request\Context;
use Vend\Vacation\Request\RequestInterface;

interface ProcessorInterface
{
    /**
     * @param RequestInterface $request
     * @param Context          $context
     * @return mixed
     */
    public function process(RequestInterface $request, Context $context);
}
