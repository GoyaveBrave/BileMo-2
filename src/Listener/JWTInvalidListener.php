<?php

namespace App\Listener;

use App\Responder\Interfaces\JsonResponderInterface;
use App\Responder\JsonResponder;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTInvalidEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Response\JWTAuthenticationFailureResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class JWTInvalidListener
{
    /**
     * @var JsonResponderInterface
     */
    private $jsonResponder;
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * JWTInvalidListener constructor.
     *
     * @param JsonResponderInterface $jsonResponder
     * @param UrlGeneratorInterface  $urlGenerator
     */
    public function __construct(JsonResponderInterface $jsonResponder, UrlGeneratorInterface $urlGenerator)
    {
        $this->jsonResponder = $jsonResponder;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @param JWTInvalidEvent $event
     */
    public function onJWTInvalid(JWTInvalidEvent $event)
    {
        $responder = $this->jsonResponder;

        $data = [
            'error' => [
                'code' => JsonResponse::HTTP_UNAUTHORIZED,
                'message' => 'Token invalide, pour avoir un token valide : '.$this->urlGenerator->generate('login_check', [], 0),
            ],
        ];

        $event->setResponse($responder(new Request(), $data, JsonResponse::HTTP_UNAUTHORIZED, JsonResponder::CONTENT_TYPE_JSON));
    }
}
