<?php

declare(strict_types=1);

namespace Polidog\SimpleApiBundle\Event;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * @template T
 */
final class ViewParameterEvent extends Event
{
    private Request $request;
    private bool $masterRequest;

    /**
     * @var T
     */
    private $data;

    /**
     * @param T $data
     */
    public function __construct(Request $request, bool $masterRequest, $data)
    {
        $this->request = $request;
        $this->masterRequest = $masterRequest;
        $this->data = $data;
    }

    /**
     * @return T
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param T $data
     */
    public function setData($data): void
    {
        $this->data = $data;
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
