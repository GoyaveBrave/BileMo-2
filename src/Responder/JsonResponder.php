<?php

namespace App\Responder;

use App\Responder\Interfaces\JsonResponderInterface;
use DateTime;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;

class JsonResponder implements JsonResponderInterface
{
    /**
     * @var SerializerInterface
     */
    private $serializer;
    const CONTENT_TYPE_JSON = ['Content-Type' => 'application/json'];

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function __invoke(Request $request, $data, int $status = 200, array $headers = [], DateTime $lastModified = null, array $context = []): JsonResponse
    {
        $json = $this->serializer->serialize($data, 'json', array_merge([
            'json_encode_options' => JSON_UNESCAPED_SLASHES,
        ], $context));

        $response = new JsonResponse($json, $status, $headers, true);

        if ($request->isMethodCacheable()) {
            $response->setCache([
                'etag' => hash('sha256', $response->getContent()),
                'max_age' => 15,
                's_maxage' => 15,
                'public' => true,
            ]);

            if ($lastModified) {
                $response->setLastModified($lastModified);
            }

            $response->headers->addCacheControlDirective('must-revalidate', true);
            $response->expire();
            $response->isNotModified($request);
        }

        return $response;
    }
}
