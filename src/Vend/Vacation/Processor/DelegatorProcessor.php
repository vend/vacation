<?php

namespace Vend\Vacation\Processor;

use Vend\Vacation\Metadata;
use Vend\Vacation\Request\Context;
use Vend\Vacation\Request\RequestInterface;
use Symfony\Component\Config\Definition\Processor as ConfigProcessor;

class DelegatorProcessor implements ProcessorInterface
{
    const PROCESSOR_DEFAULT = 'default';
    const PROCESSOR_PAYLOAD = 'payload';

    /**
     * @var \ArrayObject
     */
    private $processors;

    public function __construct()
    {
        $this->processors = new \ArrayObject([
            self::PROCESSOR_DEFAULT => new DefaultProcessor(),
            self::PROCESSOR_PAYLOAD => new PayloadProcessor(),
        ]);
    }

    /**
     * @param string                       $name
     * @param ProcessorInterface $processor
     */
    public function registerProcessor($name, ProcessorInterface $processor)
    {
        $this->processors->offsetSet($name, $processor);
    }

    /**
     * @param string $name
     * @throws \InvalidArgumentException
     * @return ProcessorInterface
     */
    protected function getProcessor($name)
    {
        if (!$this->processors->offsetExists($name)) {
            throw new \InvalidArgumentException(sprintf('Unknown processor "%s".', $name));
        }

        return $this->processors->offsetGet($name);
    }

    /**
     * @param RequestInterface $request
     * @param Context          $context
     * @return mixed
     */
    public function process(RequestInterface $request, Context $context)
    {
        if ($context->getOperationMetadata()->processor) {
            $processorName = $context->getOperationMetadata()->processor->name;
        } else {
            switch ($request->getMethod()) {
                case RequestInterface::METHOD_POST:
                case RequestInterface::METHOD_PUT:
                    $processorName = self::PROCESSOR_PAYLOAD;
                    break;
                default:
                    $processorName = self::PROCESSOR_DEFAULT;
            }
        }

        $processor = $this->getProcessor($processorName);

        if ($processor instanceof ConfigurableProcessor) {
            $configurationProcessor = new ConfigProcessor();
            $configuration = $configurationProcessor->processConfiguration(
                $processor,
                [$context->getOperationMetadata()->processor->options]
            );
            $processor->configure($configuration);
        }

        return $processor->process($request, $context);
    }
}
