<?php

namespace Vend\Vacation\Processor;

use Vend\Vacation\Request\Context;
use Vend\Vacation\Request\RequestInterface;

class DefaultProcessor implements ProcessorInterface
{
    use ParameterableProcessor;

    /**
     * @param RequestInterface $request
     * @param Context          $context
     * @return mixed
     */
    public function process(RequestInterface $request, Context $context)
    {
        $arguments = [
            $this->getParameters($request, $context)
        ];

        return $context->getOperationMetadata()->invoke($context->getController(), $arguments);
    }
}
