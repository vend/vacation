<?php

namespace Vend\Vacation;

use Vend\Vacation\Error;
use Vend\Vacation\Metadata;
use Vend\Vacation\Controller;
use Vend\Vacation\Operation;
use Vend\Vacation\Processor;
use Vend\Vacation\Request;
use Vend\Vacation\Response;
use Vend\Vacation\Validation;

class Engine implements EngineInterface
{
    /**
     * @var Request\ResolverInterface
     */
    private $resolver;

    /**
     * @var Response\BuilderInterface
     */
    private $responseBuilder;

    /**
     * @var Processor\DelegatorProcessor
     */
    private $processor;

    /**
     * @param Request\ResolverInterface    $resolver
     * @param Processor\DelegatorProcessor $processor
     * @param Response\BuilderInterface    $responseBuilder
     */
    public function __construct(
        Request\ResolverInterface $resolver,
        Processor\DelegatorProcessor $processor,
        Response\BuilderInterface $responseBuilder
    ) {
        $this->resolver        = $resolver;
        $this->processor       = $processor;
        $this->responseBuilder = $responseBuilder;
    }

    /**
     * @param Request\RequestInterface $request
     * @throws \Exception
     * @return Response\ResponseInterface
     */
    public function execute(Request\RequestInterface $request)
    {
        try {
            $context = $this->resolver->resolve($request);
        } catch (\Exception $e) {
            return $this->responseBuilder->create(
                $request,
                new Error\Server('Could not resolve context', Response\ResponseInterface::STATUS_SERVER_ERROR, $e)
            );
        }

        if (!($controller = $context->getController())) {
            return $this->responseBuilder->create(
                $request,
                new Error\Client('Not Found', Response\ResponseInterface::STATUS_NOT_FOUND)
            );
        }

        if (!($operationMetadata = $context->getOperationMetadata())) {
            return $this->responseBuilder->create(
                $request,
                new Error\Client('Method Not Allowed', Response\ResponseInterface::STATUS_METHOD_NOT_ALLOWED)
            );
        }

        try {
            $result = $this->processor->process($request, $context);
        } catch (\Exception $e) {
            $result = new Error\Server('An unknown error has occurred', Response\ResponseInterface::STATUS_SERVER_ERROR, $e);
        }

        // Return the result decorated as a response
        return $this->responseBuilder->create($request, $result);
    }
}
