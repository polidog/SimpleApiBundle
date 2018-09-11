<?php

declare(strict_types=1);

namespace Polidog\SimpleApiBundle\ResponseHandler;

use Symfony\Component\HttpFoundation\Response;

interface HandlerInterface
{
    /**
     * @param string $format
     *
     * @return bool
     */
    public function support(string $format): bool;

    /**
     * @param array         $parameters
     * @param Response|null $response
     *
     * @return Response
     */
    public function handle(array $parameters, Response $response = null): Response;
}
