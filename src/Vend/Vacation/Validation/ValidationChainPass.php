<?php

namespace Vend\Vacation\Validation;

use Vend\Vacation\Metadata\Operation;
use Vend\Vacation\Operation\ArgumentBag;
use Vend\Vacation\Request\RequestInterface;

class ValidationChainPass implements ValidationPassInterface
{
    /**
     * @var \ArrayObject
     */
    private $passes;

    public function __construct()
    {
        $this->passes  = new \ArrayObject();
    }

    /**
     * @param ValidationPassInterface $pass
     */
    public function registerValidationPass(ValidationPassInterface $pass)
    {
        $this->passes->append($pass);
    }

    /**
     * @param RequestInterface $request
     * @param object           $controller
     * @param Operation        $operationMetadata
     * @param ArgumentBag      $operationArguments
     * @return bool
     */
    public function validate(RequestInterface $request, $controller, Operation $operationMetadata, ArgumentBag $operationArguments)
    {
        // Todo merge multiple pass results
        foreach ($this->passes->getIterator() as $pass) {
            /** @var ValidationPassInterface $pass */
            $result = $pass->validate($request, $controller, $operationMetadata, $arguments);

            if (true !== $result) {
                return $result;
            }
        }

        return true;
    }
}
