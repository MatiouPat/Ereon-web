<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserPasswordHasher implements ProcessorInterface
{

    public function __construct(private readonly ProcessorInterface $processorInterface, private readonly UserPasswordHasherInterface $userPasswordHasherInterface)
    {

    }

    public function process($data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        if (!$data->getPlainPassword()) {
            return $this->processorInterface->process($data, $operation, $uriVariables, $context);
        }

        $hashedPassword = $this->userPasswordHasherInterface->hashPassword(
            $data,
            $data->getPlainPassword()
        );
        $data->setPassword($hashedPassword);
        $data->eraseCredentials();

        return $this->processorInterface->process($data, $operation, $uriVariables, $context);
    }

}