<?php

declare(strict_types=1);

namespace Polidog\SimpleApiBundle\Exception;

class ErrorException extends \RuntimeException
{
    private $data;

    /**
     * @param mixed $data
     */
    public static function newException(int $statusCode, array $data): self
    {
        $self = new self();
        $self->code = $statusCode;
        $self->data = $data;

        return $self;
    }

    public function getData()
    {
        return $this->data;
    }
}
