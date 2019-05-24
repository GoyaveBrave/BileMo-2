<?php

namespace App\Listener;

use App\Exceptions\BadRequestException;
use App\Responder\Interfaces\JsonResponderInterface;
use App\Responder\JsonResponder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

class BadRequestExceptionListener
{
    /**
     * @var JsonResponderInterface
     */
    private $jsonResponder;

    /**
     * NotFoundExceptionListener constructor.
     *
     * @param JsonResponderInterface $jsonResponder
     */
    public function __construct(JsonResponderInterface $jsonResponder)
    {
        $this->jsonResponder = $jsonResponder;
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();
        $responder = $this->jsonResponder;

        if ($exception instanceof BadRequestException) {
            $data = [
                'error' => [
                    'code' => Response::HTTP_BAD_REQUEST,
                    'message' => $exception->getMessage(),
                ],
            ];

            $event->setResponse($responder($event->getRequest(), $data, Response::HTTP_BAD_REQUEST, JsonResponder::CONTENT_TYPE_JSON));
        }
    }
}
