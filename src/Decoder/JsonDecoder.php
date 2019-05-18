<?php

declare(strict_types=1);

namespace Polidog\SimpleApiBundle\Decoder;

class JsonDecoder implements DecoderInterface
{
    public function support(string $format): bool
    {
        return 'json' === $format;
    }

    public function decode(string $data): array
    {
        if (0 === \strlen($data)) {
            return [];
        }

        return @json_decode($data, true);
    }
}
