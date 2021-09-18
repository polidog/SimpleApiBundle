<?php

declare(strict_types=1);

namespace Polidog\SimpleApiBundle\Event;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\EventDispatcher\Event;

class ViewParameterEvent extends Event
{
    private array $parameters;

    private Request $request;

    private bool $masterRequest;

    public function __construct(array $parameters, Request $request, bool $masterRequest)
    {
        $this->parameters = $parameters;
        $this->request = $request;
        $this->masterRequest = $masterRequest;
    }

    final public function merge(array $parameters): void
    {
        $this->parameters = array_merge($this->parameters, $parameters);
    }

    final public function getParameters(): array
    {
        return $this->parameters;
    }

    final public function setParameters(array $parameters): void
    {
        $this->parameters = $parameters;
    }

    final public function getRequest(): Request
    {
        return $this->request;
    }

    final public function isMasterRequest(): bool
    {
        return $this->masterRequest;
    }
}
