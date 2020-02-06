<?php

declare(strict_types=1);

namespace Polidog\SimpleApiBundle\ResponseHandler;

use Polidog\SimpleApiBundle\Exception\UnsupportedMediaTypeHttpException;

interface HandlerProviderInterface
{
    /**
     * Checks if a certain format is supported.
     */
    public function supports(string $format): bool;

    /**
     * Provides decoders, possibly lazily.
     *
     * @throws UnsupportedMediaTypeHttpException
     */
    public function getHandler(string $format): HandlerInterface;

    public function add(HandlerInterface $handler): void;
}
