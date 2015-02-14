<?php

namespace Vend\Vacation\Metadata\Driver;

use Doctrine\Common\Annotations\Reader;
use Vend\Vacation\Annotation\Endpoint;
use Vend\Vacation\Annotation\Operation;
use Vend\Vacation\Annotation\Processor;
use Vend\Vacation\Metadata;
use Metadata\Driver\DriverInterface;

class Annotation implements DriverInterface
{
    /**
     * @var Reader
     */
    private $reader;

    /**
     * @param Reader $reader
     */
    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }

    /**
     * @param \ReflectionClass $class
     * @throws \InvalidArgumentException
     * @return \Metadata\ClassMetadata
     */
    public function loadMetadataForClass(\ReflectionClass $class)
    {
        /** @var Endpoint $endpointAnnotation */
        $endpointAnnotation = $this->reader->getClassAnnotation($class, 'Vend\\Vacation\\Annotation\\Endpoint');

        if (null === $endpointAnnotation) {
            throw new \InvalidArgumentException(sprintf('Class "%s" is not annotated as a controller.', $class->getName()));
        }

        $controllerMetadata = new Metadata\Controller($class->getName());
        /** @var Metadata\Endpoint[] $endpoints */
        $endpoints = [];

        foreach ($endpointAnnotation->getPaths() as $name => $path) {
            $endpointMetadata = new Metadata\Endpoint($class->getName());
            $endpointMetadata->path = $path;
            $endpoints[$name] = $endpointMetadata;
        }

        foreach ($class->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
            /** @var Operation $operationAnnotation */
            $operationAnnotation = $this->reader->getMethodAnnotation($method, 'Vend\\Vacation\\Annotation\\Operation');

            if (null === $operationAnnotation) {
                continue;
            }

            $operationMetadata = new Metadata\Operation($class->getName(), $method->getName());
            $operationMetadata->requestMethod = $operationAnnotation->getRequestMethod();
            $operationMetadata->parameters    = $operationAnnotation->getParameters();

            /** @var Processor $processorAnnotation */
            $processorAnnotation = $this->reader->getMethodAnnotation($method, 'Vend\\Vacation\\Annotation\\Processor');

            if ($processorAnnotation) {
                $processorMetadata = new Metadata\Processor();
                $processorMetadata->name = $processorAnnotation->getName();
                $processorMetadata->options = $processorAnnotation->getOptions();
                $operationMetadata->processor = $processorMetadata;
            }

            $endpoints[$operationAnnotation->getEndpoint()]->operations[] = $operationMetadata;
        }

        $controllerMetadata->endpoints = $endpoints;

        return $controllerMetadata;
    }
}
