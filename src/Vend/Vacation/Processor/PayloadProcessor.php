<?php

namespace Vend\Vacation\Processor;

use Vend\Vacation\Metadata;
use Vend\Vacation\Request\Context;
use Vend\Vacation\Request\RequestInterface;

class PayloadProcessor implements ProcessorInterface
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
            $request->getPayloadAsArray(),
            $this->getParameters($request, $context),
        ];

        return $context->getOperationMetadata()->invoke($context->getController(), $arguments);
    }
}
