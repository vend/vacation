<?php

namespace Vend\Vacation\Response;

interface FactoryInterface
{
    /**
     * @return ResponseInterface
     */
    public function get();
}
