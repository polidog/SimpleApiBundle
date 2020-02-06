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
     * @param SerializerInterface $serializer
     */
    public function __construct(bool $useJmsSerializer, SerializerInterface $serializer = null)
    {
        $this->useJmsSerializer = $useJmsSerializer;
        $this->serializer = $serializer;
    }

    public function support(string $format): bool
    {
        return self::FORMAT === $format;
    }

    public function handle(array $parameters, Response $response = null): Response
    {
        if (false === $this->useJmsSerializer || null === $this->serializer) {
            return new JsonResponse($parameters);
        }
        $json = $this->serializer->serialize($parameters, self::FORMAT);

        return JsonResponse::fromJsonString($json);
    }
}
