<?php

namespace Vend\Vacation\Controller;

use Vend\Vacation\Metadata;
use Vend\Vacation\Request\RequestInterface;

class EndpointMatcher implements EndpointMatcherInterface
{
    /**
     * @var string
     */
    private $prefix;

    /**
     * @param string $prefix
     */
    public function __construct($prefix = '')
    {
        $this->prefix = $prefix;
    }

    /**
     * @param string $path
     * @return string
     */
    protected function normalisePath($path)
    {
        return trim($path, '/');
    }

    /**
     * @param RequestInterface $request
     * @param string           $path
     * @throws \RuntimeException
     * @return string
     */
    protected function resolvePath(RequestInterface $request, $path)
    {
        $pathSections = explode('/', $path);

        // Substitute parameter placeholders with their values to match request URI
        foreach ($pathSections as &$section) {
            if (0 === strpos($section, ':')) {
                $parameterName = substr($section, 1);
                if ($parameter = $request->getParameter($parameterName)) {
                    $section = $parameter;
                } else {
                    throw new \RuntimeException(sprintf('Required path parameter "%s" not found.', $parameterName));
                }
            }
        }

        return implode('/', $pathSections);
    }

    /**
     * @param RequestInterface  $request
     * @param Metadata\Endpoint $endpointMetadata
     * @return boolean
     */
    public function matches(RequestInterface $request, Metadata\Endpoint $endpointMetadata)
    {
        $path = sprintf(
            '%s/%s',
            $this->normalisePath($this->prefix),
            $this->normalisePath($endpointMetadata->path)
        );

        try {
            $controllerPath = $this->resolvePath($request, $path);
        } catch (\RuntimeException $e) {
            return false;
        }

        $requestPath = trim(parse_url($request->getUrl(), PHP_URL_PATH), '/');

        return $controllerPath === $requestPath;
    }
}
