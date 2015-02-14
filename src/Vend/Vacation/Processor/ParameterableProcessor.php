<?php

namespace Vend\Vacation\Processor;

use Vend\Vacation\Request\Context;
use Vend\Vacation\Request\RequestInterface;

trait ParameterableProcessor
{
    public function getParameters(RequestInterface $request, Context $context)
    {
        $parameters = [];

        if (!empty($context->getOperationMetadata()->parameters)) {
            // Pluck whitelisted parameters from the request to pass to the operation as arguments
            $queryParameters = array_intersect_key(
                $request->getQueryParameters(),
                array_flip($context->getOperationMetadata()->parameters)
            );

            $parameters = array_merge($parameters, $queryParameters);
        }

        $pathSections = explode('/', trim($context->getEndpointMetadata()->path, '/'));

        // Substitute parameter placeholders with their values to match request URI
        foreach ($pathSections as $section) {
            if (0 === strpos($section, ':')) {
                $parameterName = substr($section, 1);
                if ($parameter = $request->getParameter($parameterName)) {
                    $parameters[$parameterName] = $parameter;
                }
            }
        }

        return $parameters;
    }
}
