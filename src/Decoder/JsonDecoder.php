<?php

declare(strict_types=1);

namespace Polidog\SimpleApiBundle\Decoder;

class JsonDecoder implements DecoderInterface
{
    final public function support(string $format): bool
    {
        return 'json' === $format;
    }

    /**
     * @throws \JsonException
     */
    final public function decode(string $data): array
    {
        if ('' === $data) {
            return [];
        }

        return json_decode($data, true, 512, \JSON_THROW_ON_ERROR);
    }
}
