<?php

namespace Vend\Vacation\Response;

interface ResponseInterface
{
    const CONTENT_TYPE_XML  = 'application/xml';
    const CONTENT_TYPE_JSON = 'application/json';

    const HEADER_CONTENT_TYPE = 'Content-Type';

    const STATUS_OK         = 200;
    const STATUS_CREATED    = 201;
    const STATUS_NO_CONTENT = 204;

    const STATUS_BAD_REQUEST        = 400;
    const STATUS_NOT_AUTHORIZED     = 401;
    const STATUS_NOT_FORBIDDEN      = 403;
    const STATUS_NOT_FOUND          = 404;
    const STATUS_METHOD_NOT_ALLOWED = 405;

    const STATUS_SERVER_ERROR = 500;

    /**
     * @param string $name
     * @param mixed $value
     */
    public function setHeader($name, $value);

    /**
     * @param int $statusCode
     */
    public function setStatusCode($statusCode);

    /**
     * @param string $content
     */
    public function setContent($content);
}
