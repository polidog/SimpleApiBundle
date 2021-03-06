<?php

declare(strict_types=1);

namespace Polidog\SimpleApiBundle\Event;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\EventDispatcher\Event;

class ViewParameterEvent extends Event
{
    /**
     * @var array
     */
    private $parameters;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var bool
     */
    private $masterRequest;

    public function __construct(array $parameters, Request $request, bool $masterRequest)
    {
        $this->parameters = $parameters;
        $this->request = $request;
        $this->masterRequest = $masterRequest;
    }

    public function merge(array $parameters): void
    {
        $this->parameters = array_merge($this->parameters, $parameters);
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function setParameters(array $parameters): void
    {
        $this->parameters = $parameters;
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function isMasterRequest(): bool
    {
        return $this->masterRequest;
    }
}
