<?php

namespace Vend\Vacation\Annotation;

/**
 * @Annotation
 */
class Processor
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $options;

    /**
     * @param array $config
     * @throws \RuntimeException
     */
    public function __construct($config)
    {
        if (empty($config['value'])) {
            throw new \RuntimeException('You must specify a processor name.');
        }

        $this->setName($config['value']);

        unset($config['value']);

        $this->setOptions($config);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param array $options
     */
    public function setOptions(array $options)
    {
        $this->options = $options;
    }
}
