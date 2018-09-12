<?php

declare(strict_types=1);

namespace Polidog\SimpleApiBundle\EventListener;

use Polidog\SimpleApiBundle\Decoder\DecoderProviderInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class BodySubscriber implements EventSubscriberInterface
{
    /**
     * @var DecoderProviderInterface
     */
    private $decoderProvider;

    /**
     * BodySubscriber constructor.
     *
     * @param DecoderProviderInterface $decoderProvider
     */
    public function __construct(DecoderProviderInterface $decoderProvider)
    {
        $this->decoderProvider = $decoderProvider;
    }

    public function onKernelController(FilterControllerEvent $event): void
    {
        $request = $event->getRequest();
        $annotation = $request->attributes->get('_simple_api_annotation');

        if (null === $annotation) {
            return;
        }

        if (false === $annotation->isBodyParse()) {
            return;
        }

        if (false === $this->isDecodeable($request)) {
            return;
        }

        $decodedContent = $this->decoderProvider->getDecoder($annotation->getFormat())->decode($request->getContent());
        $request->request = new ParameterBag($decodedContent);
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => ['onKernelController', 30],
        ];
    }

    protected function isDecodeable(Request $request)
    {
        if (!\in_array($request->getMethod(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            return false;
        }

        return !$this->isFormRequest($request);
    }

    private function isFormRequest(Request $request)
    {
        $contentTypeParts = explode(';', $request->headers->get('Content-Type'));

        if (isset($contentTypeParts[0])) {
            return \in_array($contentTypeParts[0], ['multipart/form-data', 'application/x-www-form-urlencoded']);
        }

        return false;
    }
}
