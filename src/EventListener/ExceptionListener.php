<?php

declare(strict_types=1);

namespace Polidog\SimpleApiBundle\EventListener;

use Polidog\SimpleApiBundle\ResponseHandler\HandlerProviderInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ExceptionListener implements EventSubscriberInterface
{
    /**
     * @var HandlerProviderInterface
     */
    private $provider;

    /**
     * ExceptionListener constructor.
     * @param HandlerProviderInterface $provider
     */
    public function __construct(HandlerProviderInterface $provider)
    {
        $this->provider = $provider;
    }


    public function onKernelException(GetResponseForExceptionEvent $event): void
    {
        $request = $event->getRequest();
        $annotation = $request->attributes->get('_simple_api_annotation');
        if (null === $annotation) {
            return;
        }

        if (false === $annotation->isUseResponseHandler()) {
            return;
        }

        $parameters = [
            'message' => $event->getException()->getMessage(),
            'code' => $event->getException()->getCode(),
        ];

        $newResponse = $this->provider->getHandler($annotation->getFormat())->handle($parameters);
        $event->setResponse($newResponse);
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => ['onKernelException'],
        ];
    }
}
