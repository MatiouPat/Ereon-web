<?php

namespace App\EventListener;

use App\Entity\Token;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Serializer\SerializerInterface;

#[AsEntityListener(event: Events::postUpdate, method: 'postUpdate', entity: Token::class)]
class TokenListener
{

    private HubInterface $hub;

    private SerializerInterface $serializer;

    public function __construct(HubInterface $hub, SerializerInterface $serializer)
    {
        $this->hub = $hub;
        $this->serializer = $serializer;
    }

    public function postUpdate(Token $token):void
    {
        $update = new Update('https://lescanardsmousquetaires.fr/token', $this->serializer->serialize($token, 'json', ['groups' => 'token:read']));
        $this->hub->publish($update);
    }

}