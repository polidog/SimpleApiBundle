<?php

declare(strict_types=1);

namespace Polidog\SimpleApiBundle\ResponseHandler;

use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class JsonHandler implements HandlerInterface
{
    private const FORMAT = 'json';

    private bool $useJmsSerializer;

    private ?SerializerInterface $serializer;

    public function __construct(bool $useJmsSerializer, SerializerInterface $serializer = null)
    {
        $this->useJmsSerializer = $useJmsSerializer;
        $this->serializer = $serializer;
    }

    final public function support(string $format): bool
    {
        return self::FORMAT === $format;
    }

    final public function handle($parameters, array $groups = [], string $version = null): Response
    {
        if (null === $parameters) {
            return new JsonResponse();
        }

        if (false === $this->useJmsSerializer || null === $this->serializer) {
            return new JsonResponse($parameters);
        }

        $context = SerializationContext::create();
        $context->setSerializeNull(true);
        if (!empty($groups)) {
            $context->setGroups(array_merge(['Default'], $groups));
        }
        if (null !== $version) {
            $context->setVersion($version);
        }

        $json = $this->serializer->serialize($parameters, self::FORMAT, $context);

        return JsonResponse::fromJsonString($json);
    }
}
