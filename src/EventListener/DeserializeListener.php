<?php

namespace App\EventListener;

use ApiPlatform\Serializer\SerializerContextBuilderInterface;
use ApiPlatform\Symfony\EventListener\DeserializeListener as DecoratedListener;
use ApiPlatform\Util\RequestAttributesExtractor;
use App\Entity\Personage;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class DeserializeListener
{

    public function __construct(
        private DecoratedListener $decoratedListener,
        private SerializerContextBuilderInterface $serializerContextBuilder,
        private DenormalizerInterface $denormalizer
    ){}

    public function onKernelRequest(RequestEvent $requestEvent): void
    {
        $request = $requestEvent->getRequest();
        if ($request->isMethodCacheable() || $request->isMethod(Request::METHOD_DELETE)) {
            return;
        }

        if ($request->getContentTypeFormat() === 'form') {
            $this->denormalizeFromRequest($request);
        }else {
            $this->decoratedListener->onKernelRequest($requestEvent);
        }
    }

    private function denormalizeFromRequest(Request $request): void
    {
        $attributes = RequestAttributesExtractor::extractAttributes($request);
        if(empty($attributes)) {
            return;
        }

        $context = $this->serializerContextBuilder->createFromRequest($request, false, $attributes);
        $populated = $request->attributes->get('data');
        if($populated !== null) {
            $context['object_to_populate'] = $populated;
        }
        $data = $request->request->all();
        $files = $request->files->all();
        /**
         * @var Personage
         */
        $object = $this->denormalizer->denormalize(array_merge($data, $files), $attributes['resource_class'], null, $context);
        $object->setUpdatedAt(new \DateTimeImmutable());
        $request->attributes->set('data', $object);
    }

}