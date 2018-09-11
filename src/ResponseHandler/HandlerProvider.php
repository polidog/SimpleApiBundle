<?php

declare(strict_types=1);

namespace Polidog\SimpleApiBundle\ResponseHandler;

use Polidog\SimpleApiBundle\Exception\UnsupportedMediaTypeHttpException;

class HandlerProvider implements HandlerProviderInterface
{
    /**
     * @var HandlerInterface[]
     */
    private $handlers;

    /**
     * EncoderProvider constructor.
     *
     * @param HandlerInterface[] $encoders
     */
    public function __construct(array $encoders)
    {
        $this->handlers = $encoders;
    }

    public function supports(string $format): bool
    {
        foreach ($this->handlers as $encoder) {
            if ($encoder->support($format)) {
                return true;
            }
        }

        return false;
    }

    public function getHandler(string $format): HandlerInterface
    {
        foreach ($this->handlers as $handler) {
            if ($handler->support($format)) {
                return $handler;
            }
        }

        throw new UnsupportedMediaTypeHttpException("Request body format '{$format}' not supported");
    }

    public function add(HandlerInterface $handler): void
    {
        array_unshift($this->handlers, $handler);
    }
}
