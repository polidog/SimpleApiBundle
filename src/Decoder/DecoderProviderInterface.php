<?php

declare(strict_types=1);

namespace Polidog\SimpleApiBundle\Decoder;

use Polidog\SimpleApiBundle\Exception\UnsupportedMediaTypeHttpException;

interface DecoderProviderInterface
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
     * @param $format
     *
     * @return DecoderInterface
     *
     * @throws UnsupportedMediaTypeHttpException
     */
    public function getDecoder(string $format): DecoderInterface;

    /**
     * @param DecoderInterface $decoder
     */
    public function add(DecoderInterface $decoder): void;
}
