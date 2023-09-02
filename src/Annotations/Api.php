<?php

declare(strict_types=1);

namespace Polidog\SimpleApiBundle\Annotations;

use Attribute;

/**
 * @Annotation
 *
 * @Target({"METHOD","CLASS"})
 */
#[\Attribute(\Attribute::TARGET_METHOD | \Attribute::TARGET_CLASS | \Attribute::IS_REPEATABLE)]
class Api implements ApiInterface
{
    public const FORMAT_JSON = 'json';
    private string $format;
    private int $statusCode;
    private bool $bodyParse;
    private array $groups;
    private ?string $version;

    public function __construct(int $statusCode = 200, bool $bodyParse = true, string $format = self::FORMAT_JSON, array $groups = [], string $version = null)
    {
        $this->statusCode = $statusCode;
        $this->bodyParse = $bodyParse;
        $this->format = $format;
        $this->groups = $groups;
        $this->version = $version;
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

    final public function getGroups(): array
    {
        return $this->groups;
    }

    final public function getVersion(): ?string
    {
        return $this->version;
    }
}
