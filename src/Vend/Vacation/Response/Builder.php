<?php

namespace Vend\Vacation\Response;

use Vend\Vacation\Request\RequestInterface;
use JMS\Serializer;
use Vend\Vacation\Error;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class Builder implements BuilderInterface
{
    const EVENT_RESPONSE_ADJUST = 'vacation.response.adjust';

    const FORMAT_XML  = 'xml';
    const FORMAT_JSON = 'json';

    /**
     * @var FactoryInterface
     */
    private $responseFactory;

    /**
     * @var Serializer\SerializerInterface
     */
    private $serializer;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var Serializer\SerializationContext
     */
    private $serializationContext;

    /**
     * @param FactoryInterface                $responseFactory
     * @param Serializer\SerializerInterface  $serializer
     * @param EventDispatcherInterface        $eventDispatcher
     * @param Serializer\SerializationContext $serializationContext
     */
    public function __construct(
        FactoryInterface $responseFactory,
        Serializer\SerializerInterface $serializer,
        EventDispatcherInterface $eventDispatcher,
        Serializer\SerializationContext $serializationContext = null
    ) {
        $this->responseFactory      = $responseFactory;
        $this->serializer           = $serializer;
        $this->eventDispatcher      = $eventDispatcher;
        $this->serializationContext = $serializationContext;
    }

    /**
     * @return Serializer\SerializationContext
     */
    protected function getSerializationContext()
    {
        return $this->serializationContext;
    }

    /**
     * @param mixed  $content
     * @param string $format
     * @return string|null
     */
    protected function getPayload($content, $format)
    {
        // The int 0 would return null here, yeah?
        if (empty($content)) {
            return null;
        }

        return $this->serializer->serialize($content, $format, $this->getSerializationContext());
    }

    /**
     * @param RequestInterface $request
     * @return int
     */
    protected function getStatusCode(RequestInterface $request)
    {
        $normalizedMethod = strtoupper($request->getMethod());

        switch ($normalizedMethod) {
            case RequestInterface::METHOD_POST:
                $statusCode = ResponseInterface::STATUS_CREATED;
                break;
            case RequestInterface::METHOD_DELETE:
                $statusCode = ResponseInterface::STATUS_NO_CONTENT;
                break;
            case RequestInterface::METHOD_PUT:
            case RequestInterface::METHOD_PATCH:
            case RequestInterface::METHOD_GET:
            default:
                $statusCode = ResponseInterface::STATUS_OK;
                break;
        }

        return $statusCode;
    }

    /**
     * @param RequestInterface $request
     * @return string
     */
    protected function getFormat(RequestInterface $request)
    {
        // TODO This should be more robust to parse the header well
        // Content negotiation
        switch ($request->getHeader(RequestInterface::HEADER_ACCEPT, ResponseInterface::CONTENT_TYPE_JSON)) {
            case ResponseInterface::CONTENT_TYPE_JSON:
            default:
                return self::FORMAT_JSON;
        }
    }

    /**
     * @param RequestInterface $request
     * @return string
     */
    protected function getContentType(RequestInterface $request)
    {
        switch ($this->getFormat($request)) {
            case self::FORMAT_JSON:
            default:
                return ResponseInterface::CONTENT_TYPE_JSON;
        }
    }

    /**
     * @param RequestInterface $request
     * @param mixed            $content
     * @return ResponseInterface
     */
    public function create(RequestInterface $request, $content = null)
    {
        $response = $this->responseFactory->get();
        $response->setHeader(ResponseInterface::HEADER_CONTENT_TYPE, $this->getContentType($request));

        $payload = $this->getPayload($content, $this->getFormat($request));
        $response->setContent($payload);

        if ($content instanceof Error\AbstractError) {
            $response->setStatusCode($content->getCode());
        } else {
            $response->setStatusCode(
                empty($payload) ? ResponseInterface::STATUS_NO_CONTENT : $this->getStatusCode($request)
            );
        }

        // Provide the ability to modify the response before sending
        $adjustment = new Adjustment();
        $this->eventDispatcher->dispatch(self::EVENT_RESPONSE_ADJUST, new GenericEvent($adjustment));
        $adjustment->adjust($response);

        return $response;
    }
}
