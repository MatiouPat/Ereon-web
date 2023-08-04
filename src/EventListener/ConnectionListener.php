<?php

namespace App\EventListener;

use App\Entity\Connection;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Serializer\SerializerInterface;

#[AsEntityListener(event: Events::postUpdate, method: 'postUpdate', entity: Connection::class)]
class ConnectionListener 
{

    private HubInterface $hub;

    private SerializerInterface $serializer;

    public function __construct(HubInterface $hub, SerializerInterface $serializer)
    {
        $this->hub = $hub;
        $this->serializer = $serializer;
    }

    public function postUpdate(Connection $connection):void
    {
        $update = new Update('https://lescanardsmousquetaires.fr/connection/' . $connection->getId(), $this->serializer->serialize($connection, 'json', ['groups' => 'connection:read']));
        $this->hub->publish($update);
    }

}