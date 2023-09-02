<?php

declare(strict_types=1);

namespace Polidog\SimpleApiBundle\EventListener;

use Polidog\SimpleApiBundle\Annotations\Api;
use Polidog\SimpleApiBundle\Event\ViewParameterEvent;
use Polidog\SimpleApiBundle\Events;
use Polidog\SimpleApiBundle\ResponseHandler\HandlerProviderInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ViewListener implements EventSubscriberInterface
{
    private HandlerProviderInterface $provider;

    private EventDispatcherInterface $eventDispatcher;

    public function __construct(HandlerProviderInterface $provider, EventDispatcherInterface $eventDispatcher)
    {
        $this->provider = $provider;
        $this->eventDispatcher = $eventDispatcher;
    }

    final public function onKernelView(ViewEvent $event): void
    {
        $request = $event->getRequest();
        $annotation = $request->attributes->get('_simple_api_annotation');
        if (null === $annotation) {
            return;
        }
        \assert($annotation instanceof Api);

        $result = $event->getControllerResult();

        if ($result instanceof Response) {
            return;
        }

        $masterRequest = method_exists($event, 'isMainRequest') ? $event->isMainRequest() : $event->isMasterRequest();
        $viewEvent = new ViewParameterEvent($request, $masterRequest, $result);
        $this->eventDispatcher->dispatch($viewEvent, Events::VIEW_PARAMETERS);
        $newResponse = $this->provider
            ->getHandler($annotation->getFormat())
            ->handle(
                $viewEvent->getData(),
                $annotation->getGroups(),
                $annotation->getVersion()
            );
        $newResponse->setStatusCode($annotation->getStatusCode());
        $event->setResponse($newResponse);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['onKernelView'],
        ];
    }
}
