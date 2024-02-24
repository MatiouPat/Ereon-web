<?php

namespace App\Serializer;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class FileDenormalizer implements DenormalizerInterface
{

    public function denormalize($data, string $type, string $format = null, array $context = []): array
    {
        return $data;
    }

    public function supportsDenormalization($data, string $type, string $format = null, array $context = []): bool
    {
        return $data instanceof File;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            File::class => true,
        ];
    }

}