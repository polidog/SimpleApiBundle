<?php

declare(strict_types=1);

namespace Polidog\SimpleApiBundle\ResponseHandler;

use Polidog\SimpleApiBundle\Exception\UnsupportedMediaTypeHttpException;

interface HandlerProviderInterface
{
    /**
     * Checks if a certain format is supported.
     *
     * @param string $format
     *
     * @return bool
     */
    public function supports(string $format): bool;

    /**
     * Provides decoders, possibly lazily.
     *
     * @param string $format
     *
     * @return HandlerInterface
     *
     * @throws UnsupportedMediaTypeHttpException
     */
    public function getHandler(string $format): HandlerInterface;

    /**
     * @param HandlerInterface $handler
     */
    public function add(HandlerInterface $handler): void;
}
