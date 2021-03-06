<?php

declare(strict_types=1);

namespace Polidog\SimpleApiBundle\Exception;

class ErrorException extends \RuntimeException
{
    private $data;

    /**
     * @param $data
     *
     * @return ErrorException
     */
    public static function newException(int $statusCode, $data): self
    {
        $self = new self();
        $self->code = $statusCode;
        $self->data = $data;

        return $self;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }
}
