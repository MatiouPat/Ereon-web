<?php

namespace App\EventListener;

use App\Entity\Token;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Serializer\SerializerInterface;

#[AsEntityListener(event: Events::postPersist, method: 'postPersist', entity: Token::class)]
#[AsEntityListener(event: Events::postUpdate, method: 'postUpdate', entity: Token::class)]
#[AsEntityListener(event: Events::preRemove, method: 'preRemove', entity: Token::class)]
class TokenListener
{
    private MessageBusInterface $bus;

    private SerializerInterface $serializer;

    public function __construct(MessageBusInterface $bus, SerializerInterface $serializer)
    {
        $this->bus = $bus;
        $this->serializer = $serializer;
    }

    /**
     * Send information to client-side following token creation
     *
     * @param Token $token
     * @return void
     */
    public function postPersist(Token $token):void
    {
        $update = new Update('https://lescanardsmousquetaires.fr/tokens', $this->serializer->serialize($token, 'jsonld', ['groups' => 'token:read']));
        $this->bus->dispatch($update);
    }

    /**
     * Send information to client-side following token modification
     *
     * @param Token $token
     * @return void
     */
    public function postUpdate(Token $token):void
    {
        $update = new Update('https://lescanardsmousquetaires.fr/tokens/' . $token->getId(), $this->serializer->serialize($token, 'jsonld', ['groups' => 'token:read']));
        $this->bus->dispatch($update);
    }

    /**
     * Send information to client-side following token deletion
     *
     * @param Token $token
     * @return void
     */
    public function preRemove(Token $token):void
    {
        $update = new Update('https://lescanardsmousquetaires.fr/tokens/' . $token->getId());
        $this->bus->dispatch($update);
    }

}