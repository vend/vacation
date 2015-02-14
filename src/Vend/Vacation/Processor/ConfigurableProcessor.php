<?php

namespace Vend\Vacation\Processor;

use Vend\Vacation\Request\Context;
use Vend\Vacation\Request\RequestInterface;
use Symfony\Component\Config\Definition\ConfigurationInterface;

abstract class ConfigurableProcessor implements ProcessorInterface, ConfigurationInterface
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @param RequestInterface $request
     * @param Context          $context
     * @return mixed
     */
    abstract protected function doProcess(RequestInterface $request, Context $context);

    /**
     * @param array $config
     */
    public function configure(array $config)
    {
        $this->config = $config;
    }

    /**
     * @param RequestInterface $request
     * @param Context          $context
     * @return mixed
     */
    final public function process(RequestInterface $request, Context $context)
    {
        $result = $this->doProcess($request, $context);
        $this->config = null;
        return $result;
    }
}
