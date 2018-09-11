<?php

declare(strict_types=1);

namespace Polidog\SimpleApiBundle\Decoder;

interface DecoderInterface
{
    /**
     * @param string $format
     *
     * @return bool
     */
    public function support(string $format): bool;

    /**
     * @param string $data
     *
     * @return mixed
     */
    public function decode(string $data): array;
}
