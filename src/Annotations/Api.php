<?php

declare(strict_types=1);

namespace Polidog\SimpleApiBundle\Annotations;

/**
 * @Annotation
 * @Target({"METHOD","CLASS"})
 */
class Api implements ApiInterface
{
    const FORMAT_JSON = 'json';

    /**
     * @var string
     */
    private $format = self::FORMAT_JSON;

    /**
     * @var int
     */
    private $statusCode = 200;

    /**
     * @var bool
     */
    private $bodyParse = true;

    /**
     * Api constructor.
     *
     * @param $type
     * @param $responseType
     */
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

    /**
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function isBodyParse(): bool
    {
        return $this->bodyParse;
    }

    public function isUseResponseHandler(): bool
    {
        return $this->useResponseHandler;
    }
}
