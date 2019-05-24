<?php

namespace App\Listener;

use App\Exceptions\NotFoundException;
use App\Responder\Interfaces\JsonResponderInterface;
use App\Responder\JsonResponder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

class NotFoundExceptionListener
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

        if ($exception instanceof NotFoundException) {

            $data = [
                'error' => [
                    'code' => Response::HTTP_NOT_FOUND,
                    'message' => $exception->getMessage(),
                ],
            ];

            $event->setResponse($responder($event->getRequest(), $data, Response::HTTP_NOT_FOUND, JsonResponder::CONTENT_TYPE_JSON));
        }
    }
}
