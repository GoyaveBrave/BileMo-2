<?php

namespace App\Listener;

use App\Responder\Interfaces\JsonResponderInterface;
use App\Responder\JsonResponder;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTExpiredEvent;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class JWTExpiredListener
{
    /**
     * @var JsonResponderInterface
     */
    private $jsonResponder;
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    public function __construct(JsonResponderInterface $jsonResponder, UrlGeneratorInterface $urlGenerator)
    {
        $this->jsonResponder = $jsonResponder;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @param JWTExpiredEvent $event
     */
    public function onJWTExpired(JWTExpiredEvent $event)
    {
        $responder = $this->jsonResponder;

        $data = [
            'error' => [
                'code' => '401 Unauthorized',
                'message' => 'Le token a expiré, merci de le renouveler en allant sur '.$this->urlGenerator->generate('login_check'),
            ],
        ];

        $response = $responder(new Request(), $data, JsonResponse::HTTP_UNAUTHORIZED, JsonResponder::CONTENT_TYPE_JSON);

        $event->setResponse($response);
    }
}
