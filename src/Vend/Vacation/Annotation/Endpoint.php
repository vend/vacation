<?php

namespace Vend\Vacation\Annotation;

/**
 * @Annotation
 */
class Endpoint
{
    /**
     * @var \ArrayObject
     */
    private $paths;

    /**
     * @param array $values
     * @throws \RuntimeException
     */
    public function __construct(array $values = [])
    {
        $this->paths = new \ArrayObject();

        if (empty($values['value'])) {
            throw new \RuntimeException('At least one path must be specified for an endpoint.');
        }

        if (is_string($values['value'])) {
            $values['value'] = ['default' => $values['value']];
        }

        foreach ($values['value'] as $name => $path) {
            if (is_numeric($name)) {
                throw new \RuntimeException('All paths must be named.');
            }

            $this->paths->offsetSet($name, $path);
        }
    }

    /**
     * @return array
     */
    public function getPaths()
    {
        return (array)$this->paths;
    }

    /**
     * @param string $name
     * @return string|null
     */
    public function getPath($name)
    {
        return $this->paths->offsetExists($name) ? $this->paths->offsetGet($name) : null;
    }
}
