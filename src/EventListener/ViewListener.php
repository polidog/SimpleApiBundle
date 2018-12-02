<?php

declare(strict_types=1);

namespace Polidog\SimpleApiBundle\EventListener;

use Polidog\SimpleApiBundle\ResponseHandler\HandlerProviderInterface;
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
     * ViewListener constructor.
     *
     * @param HandlerProviderInterface $provider
     */
    public function __construct(HandlerProviderInterface $provider)
    {
        $this->provider = $provider;
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

        if (\is_array($parameters)) {
            $newResponse = $this->provider->getHandler($annotation->getFormat())->handle($parameters);
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
