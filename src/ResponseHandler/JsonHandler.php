<?php

declare(strict_types=1);

namespace Polidog\SimpleApiBundle\ResponseHandler;

use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class JsonHandler implements HandlerInterface
{
    private const FORMAT = 'json';

    /**
     * @var bool
     */
    private $useJmsSerializer;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @param bool $useJmsSerializer
     */
    public function __construct(bool $useJmsSerializer)
    {
        $this->useJmsSerializer = $useJmsSerializer;
    }

    public function support(string $format): bool
    {
        return self::FORMAT === $format;
    }

    public function handle(array $parameters, Response $response = null): Response
    {
        if (false === $this->useJmsSerializer) {
            return new JsonResponse($parameters);
        }
        $json = $this->serializer->serialize($parameters, self::FORMAT);

        return new JsonResponse($json);
    }
}
