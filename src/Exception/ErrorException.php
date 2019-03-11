<?php

namespace Polidog\SimpleApiBundle\Exception;


class ErrorException extends \RuntimeException
{
    private $data;

    /**
     * @param int $statusCode
     * @param $data
     * @return ErrorException
     */
    public static function newException(int $statusCode, $data) :self
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