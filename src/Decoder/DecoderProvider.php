<?php

declare(strict_types=1);

namespace Polidog\SimpleApiBundle\Decoder;

use Polidog\SimpleApiBundle\Exception\UnsupportedMediaTypeHttpException;

class DecoderProvider implements DecoderProviderInterface
{
    /**
     * @var DecoderInterface[]
     */
    private array $decoders;

    /**
     * DecoderProvider constructor.
     *
     * @param DecoderInterface[] $decoders
     */
    public function __construct(array $decoders)
    {
        $this->decoders = $decoders;
    }

    public function supports(string $format): bool
    {
        foreach ($this->decoders as $decoder) {
            if ($decoder->support($format)) {
                return true;
            }
        }

        return false;
    }

    public function getDecoder(string $format): DecoderInterface
    {
        foreach ($this->decoders as $decoder) {
            if ($decoder->support($format)) {
                return $decoder;
            }
        }

        throw new UnsupportedMediaTypeHttpException("Request body format '$format' not supported");
    }

    public function add(DecoderInterface $decoder): void
    {
        array_unshift($this->decoders, $decoder);
    }
}
