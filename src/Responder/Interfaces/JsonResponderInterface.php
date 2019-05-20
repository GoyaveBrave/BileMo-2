<?php

namespace App\Responder\Interfaces;

use DateTime;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;

interface JsonResponderInterface
{
    public function __construct(SerializerInterface $serializer);

    public function __invoke(Request $request, $data, int $status = 200, array $headers = [], DateTime $lastModified = null, array $context = []): JsonResponse;
}
