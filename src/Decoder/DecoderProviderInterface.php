<?php

declare(strict_types=1);

namespace Polidog\SimpleApiBundle\Decoder;

use Polidog\SimpleApiBundle\Exception\UnsupportedMediaTypeHttpException;

interface DecoderProviderInterface
{
    /**
     * Checks if a certain format is supported.
     */
    public function supports(string $format): bool;

    /**
     * @param $format
     *
     * @throws UnsupportedMediaTypeHttpException
     */
    public function getDecoder(string $format): DecoderInterface;

    public function add(DecoderInterface $decoder): void;
}
