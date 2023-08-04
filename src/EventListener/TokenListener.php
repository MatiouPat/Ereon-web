<?php

namespace App\EventListener;

use App\Entity\Token;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Serializer\SerializerInterface;

#[AsEntityListener(event: Events::postPersist, method: 'postPersist', entity: Token::class)]
#[AsEntityListener(event: Events::postUpdate, method: 'postUpdate', entity: Token::class)]
#[AsEntityListener(event: Events::preRemove, method: 'preRemove', entity: Token::class)]
class TokenListener
{

    private HubInterface $hub;

    private SerializerInterface $serializer;

    public function __construct(HubInterface $hub, SerializerInterface $serializer)
    {
        $this->hub = $hub;
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
        $update = new Update('https://lescanardsmousquetaires.fr/token/post', $this->serializer->serialize($token, 'json', ['groups' => 'token:read']));
        $this->hub->publish($update);
    }

    /**
     * Send information to client-side following token modification
     *
     * @param Token $token
     * @return void
     */
    public function postUpdate(Token $token):void
    {
        $update = new Update('https://lescanardsmousquetaires.fr/token/update', $this->serializer->serialize($token, 'json', ['groups' => 'token:read']));
        $this->hub->publish($update);
    }

    /**
     * Send information to client-side following token deletion
     *
     * @param Token $token
     * @return void
     */
    public function preRemove(Token $token):void
    {
        $update = new Update('https://lescanardsmousquetaires.fr/token/remove', $this->serializer->serialize($token, 'json', ['groups' => 'token:read']));
        $this->hub->publish($update);
    }

}