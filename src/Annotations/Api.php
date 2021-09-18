<?php

declare(strict_types=1);

namespace Polidog\SimpleApiBundle\Annotations;

/**
 * @Annotation
 * @Target({"METHOD","CLASS"})
 */
class Api implements ApiInterface
{
    public const FORMAT_JSON = 'json';
    private string $format = self::FORMAT_JSON;
    private int $statusCode = 200;
    private bool $bodyParse = true;

    public function __construct(array $params)
    {
        if (isset($params['value'])) {
            $this->statusCode = (int) $params['value'];
        }
        foreach (['type', 'bodyParse', 'statusCode'] as $target) {
            if (isset($params[$target])) {
                $this->$target = $params[$target];
            }
        }
    }

    final public function getFormat(): string
    {
        return $this->format;
    }

    final public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    final public function isBodyParse(): bool
    {
        return $this->bodyParse;
    }
}
