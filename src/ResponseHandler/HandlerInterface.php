<?php

declare(strict_types=1);

namespace Polidog\SimpleApiBundle\ResponseHandler;

use Symfony\Component\HttpFoundation\Response;

interface HandlerInterface
{
    public function support(string $format): bool;

    public function handle(array $parameters, Response $response = null): Response;
}
