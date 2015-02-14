<?php

namespace Vend\Vacation\Operation;

use Vend\Vacation\Metadata\Controller;
use Vend\Vacation\Request\RequestInterface;
use Metadata\MetadataFactoryInterface;

class Resolver
{
    /**
     * @var MetadataFactoryInterface
     */
    private $metadataFactory;

    /**
     * @param MetadataFactoryInterface $metadataFactory
     */
    public function __construct(MetadataFactoryInterface $metadataFactory)
    {
        $this->metadataFactory = $metadataFactory;
    }

    /**
     * @param object           $controller
     * @param RequestInterface $request
     * @return \Vend\Vacation\Metadata\Operation|null
     */
    public function resolve($controller, RequestInterface $request)
    {
        /** @var Controller $controllerMetadata */
        $controllerMetadata = $this->metadataFactory->getMetadataForClass(get_class($controller));

        foreach ($controllerMetadata->operations as $operationMetadata) {
            if (strtoupper($operationMetadata->requestMethod) === strtoupper($request->getMethod())) {
                return $operationMetadata;
            }
        }

        return null;
    }
}
