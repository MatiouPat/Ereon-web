<?php

namespace App\EventListener;

use App\Entity\Connection;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Serializer\SerializerInterface;

#[AsEntityListener(event: Events::postUpdate, method: 'postUpdate', entity: Connection::class)]
class ConnectionListener 
{

    private MessageBusInterface $bus;

    private SerializerInterface $serializer;

    public function __construct(MessageBusInterface $bus, SerializerInterface $serializer)
    {
        $this->bus = $bus;
        $this->serializer = $serializer;
    }

    /**
     * Send information to the client-side following a connection modification
     * This method is used to display connected users in real time.
     *
     * @param Connection $connection
     * @return void
     */
    public function postUpdate(Connection $connection):void
    {
        $update = new Update('https://lescanardsmousquetaires.fr/connection/' . $connection->getId(), $this->serializer->serialize($connection, 'json', ['groups' => 'connection:read']));
        $this->bus->dispatch($update);
    }

}