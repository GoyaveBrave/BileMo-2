<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;

class AccessDeniedHandler implements AccessDeniedHandlerInterface
{
    /**
     * Handles an access denied failure.
     *
     * @param Request               $request
     * @param AccessDeniedException $accessDeniedException
     *
     * @return JsonResponse
     */
    public function handle(Request $request, AccessDeniedException $accessDeniedException)
    {
        $content = [
            'error' => [
                'code' => '403 Forbidden',
                'message' => "L'accès a été refusé.",
            ],
        ];

        return new JsonResponse($content, 403);
    }
}
