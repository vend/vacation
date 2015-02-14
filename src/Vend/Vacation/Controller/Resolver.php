<?php

namespace Vend\Vacation\Controller;

use Vend\Vacation\Request\RequestInterface;

class Resolver
{
    /**
     * @var Registry
     */
    private $registry;

    /**
     * @var Matcher
     */
    private $controllerMatcher;

    /**
     * @param Registry $registry
     * @param Matcher  $controllerMatcher
     */
    public function __construct(Registry $registry, Matcher $controllerMatcher)
    {
        $this->registry          = $registry;
        $this->controllerMatcher = $controllerMatcher;
    }

    /**
     * @param RequestInterface $request
     * @return mixed
     */
    public function resolve(RequestInterface $request)
    {
        foreach ($this->registry->getControllers() as $controller) {
            if ($this->controllerMatcher->matches($request, $controller)) {
                return $controller;
            }
        }

        return null;
    }
}
