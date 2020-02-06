<?php

declare(strict_types=1);

namespace Polidog\SimpleApiBundle\Decoder;

interface DecoderInterface
{
    public function support(string $format): bool;

    /**
     * @return mixed
     */
    public function decode(string $data): array;
}
