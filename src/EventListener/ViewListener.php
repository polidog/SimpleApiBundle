<?php

declare(strict_types=1);

namespace Polidog\SimpleApiBundle\EventListener;

use Polidog\SimpleApiBundle\Event\ViewParameterEvent;
use Polidog\SimpleApiBundle\Events;
use Polidog\SimpleApiBundle\ResponseHandler\HandlerProviderInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ViewListener implements EventSubscriberInterface
{
    /**
     * @var HandlerProviderInterface
     */
    private $provider;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    public function __construct(HandlerProviderInterface $provider, EventDispatcherInterface $eventDispatcher)
    {
        $this->provider = $provider;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function onKernelView(GetResponseForControllerResultEvent $event): void
    {
        $request = $event->getRequest();
        $annotation = $request->attributes->get('_simple_api_annotation');
        if (null === $annotation) {
            return;
        }

        $parameters = $event->getControllerResult();

        if ($parameters instanceof Response) {
            return;
        }

        if (null === $parameters) {
            $parameters = [];
        }

        if (\is_array($parameters)) {
            $viewEvent = new ViewParameterEvent($parameters, $request, $event->isMasterRequest());
            $this->eventDispatcher->dispatch($viewEvent, Events::VIEW_PARAMETERS);
            $newResponse = $this->provider->getHandler($annotation->getFormat())->handle($viewEvent->getParameters());
            $newResponse->setStatusCode($annotation->getStatusCode());
            $event->setResponse($newResponse);
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['onKernelView'],
        ];
    }
}
