<?php

declare(strict_types=1);

namespace Polidog\SimpleApiBundle\EventListener;

use Doctrine\Common\Annotations\Reader;
use Polidog\SimpleApiBundle\Annotations\ApiInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class AnnotationSubscriber implements EventSubscriberInterface
{
    private Reader $annotationReader;

    /**
     * ControllerSubscriber constructor.
     */
    public function __construct(Reader $annotationReader)
    {
        $this->annotationReader = $annotationReader;
    }

    /**
     * @throws \ReflectionException
     */
    public function onKernelController(ControllerEvent $event): void
    {
        $request = $event->getRequest();
        $controller = $event->getController();
        if (!\is_array($controller) && method_exists($controller, '__invoke')) {
            $controller = [$controller, '__invoke'];
        } elseif (!\is_array($controller)) {
            return;
        }

        $className = \get_class($controller[0]);
        $object = new \ReflectionClass($className);
        $method = $object->getMethod($controller[1]);

        $annotation = $this->getAnnotation($this->annotationReader->getClassAnnotations($object));
        if (null === $annotation) {
            $annotation = $this->getAnnotation($this->annotationReader->getMethodAnnotations($method));
            if (null === $annotation) {
                return;
            }
        }

        $request->attributes->set('_simple_api_annotation', $annotation);
    }

    private function getAnnotation(array $annotations): ?ApiInterface
    {
        foreach ($annotations as $annotation) {
            if ($annotation instanceof ApiInterface) {
                return $annotation;
            }
        }

        return null;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => ['onKernelController', 40],
        ];
    }
}
