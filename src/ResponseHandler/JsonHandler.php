<?php

declare(strict_types=1);

namespace Polidog\SimpleApiBundle\ResponseHandler;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class JsonHandler implements HandlerInterface
{
    public function support(string $format): bool
    {
        return 'json' === $format;
    }

    public function handle(array $parameters, Response $response = null): Response
    {
        return new JsonResponse($parameters);
    }
}
