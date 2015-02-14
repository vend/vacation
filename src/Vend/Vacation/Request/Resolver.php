<?php

namespace Vend\Vacation\Request;

use Vend\Vacation\Operation;
use Vend\Vacation\Controller;
use Vend\Vacation\Metadata;
use Metadata\MetadataFactoryInterface;

class Resolver implements ResolverInterface
{
    /**
     * @var Controller\Registry
     */
    private $registry;

    /**
     * @var Controller\EndpointMatcher
     */
    private $endpointMatcher;

    /**
     * @var Operation\Matcher
     */
    private $operationMatcher;

    /**
     * @var MetadataFactoryInterface
     */
    private $metadataFactory;

    /**
     * @param Controller\Registry                 $registry
     * @param Controller\EndpointMatcherInterface $endpointMatcher
     * @param Operation\MatcherInterface          $operationMatcher
     * @param MetadataFactoryInterface            $metadataFactory
     */
    public function __construct(
        Controller\Registry $registry,
        Controller\EndpointMatcherInterface $endpointMatcher,
        Operation\MatcherInterface $operationMatcher,
        MetadataFactoryInterface $metadataFactory
    ) {
        $this->registry         = $registry;
        $this->endpointMatcher  = $endpointMatcher;
        $this->operationMatcher = $operationMatcher;
        $this->metadataFactory  = $metadataFactory;
    }

    /**
     * @param RequestInterface $request
     * @return Context
     */
    public function resolve(RequestInterface $request)
    {
        $context = new Context();

        foreach ($this->registry->getControllers() as $controller) {
            /** @var Metadata\Controller $controllerMetadata */
            $controllerMetadata  = $this->metadataFactory->getMetadataForClass(get_class($controller));

            foreach ($controllerMetadata->endpoints as $endpointMetadata) {
                if (!$this->endpointMatcher->matches($request, $endpointMetadata)) {
                    continue;
                }

                $context->setController($controller);
                $context->setControllerMetadata($controllerMetadata);
                $context->setEndpointMetadata($endpointMetadata);

                foreach ($endpointMetadata->operations as $operationMetadata) {
                    if (!$this->operationMatcher->matches($request, $operationMetadata)) {
                        continue;
                    }

                    $context->setOperationMetadata($operationMetadata);

                    break 3;
                }
            }
        }

        return $context;
    }
}
