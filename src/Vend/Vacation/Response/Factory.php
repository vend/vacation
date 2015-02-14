<?php

namespace Vend\Vacation\Response;

class Factory implements FactoryInterface
{
    /**
     * @return ResponseInterface
     */
    public function get()
    {
        return new Response();
    }
}
