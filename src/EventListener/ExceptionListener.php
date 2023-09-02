<?php

declare(strict_types=1);

namespace Polidog\SimpleApiBundle\EventListener;

use Polidog\SimpleApiBundle\Exception\ErrorException;
use Polidog\SimpleApiBundle\ResponseHandler\HandlerProviderInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class ExceptionListener implements EventSubscriberInterface
{
    private LoggerInterface $logger;
    private HandlerProviderInterface $provider;

    public function __construct(HandlerProviderInterface $provider, LoggerInterface $logger = null)
    {
        $this->logger = $logger;
        $this->provider = $provider;
    }

    final public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $this->logException($exception, sprintf('Uncaught PHP Exception %s: "%s" at %s line %s', $exception::class, $exception->getMessage(), $exception->getFile(), $exception->getLine()));

        $request = $event->getRequest();
        $annotation = $request->attributes->get('_simple_api_annotation');
        if (null === $annotation) {
            return;
        }

        if ($exception instanceof ErrorException) {
            $parameters = [
                'errors' => $exception->getData(),
            ];
        } else {
            $parameters = [
                'errors' => [
                    'message' => $exception->getMessage(),
                ],
            ];
        }

        $statusCode = 200 > $exception->getCode() ? 500 : $exception->getCode();

        $newResponse = $this->provider->getHandler($annotation->getFormat())->handle($parameters);
        $newResponse->setStatusCode($statusCode);
        $event->setResponse($newResponse);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => ['onKernelException'],
        ];
    }

    private function logException(\Throwable $exception, string $message): void
    {
        if (null !== $this->logger) {
            if (!$exception instanceof HttpExceptionInterface || $exception->getStatusCode() >= 500) {
                $this->logger->critical($message, ['exception' => $exception]);
            } else {
                $this->logger->error($message, ['exception' => $exception]);
            }
        }
    }
}
