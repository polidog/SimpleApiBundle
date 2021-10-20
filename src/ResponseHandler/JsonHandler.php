<?php

declare(strict_types=1);

namespace Polidog\SimpleApiBundle\ResponseHandler;

use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class JsonHandler implements HandlerInterface
{
    private const FORMAT = 'json';

    private bool $useJmsSerializer;

    private ?SerializerInterface $serializer;

    public function __construct(bool $useJmsSerializer, ?SerializerInterface $serializer = null)
    {
        $this->useJmsSerializer = $useJmsSerializer;
        $this->serializer = $serializer;
    }

    final public function support(string $format): bool
    {
        return self::FORMAT === $format;
    }

    /**
     * @param mixed $parameters
     */
    final public function handle($parameters, Response $response = null): Response
    {
        if (false === $this->useJmsSerializer || null === $this->serializer) {
            return new JsonResponse($parameters);
        }
        $json = $this->serializer->serialize($parameters, self::FORMAT);

        return JsonResponse::fromJsonString($json);
    }
}
