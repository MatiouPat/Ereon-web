<?php

namespace App\EventListener;

use App\Entity\MusicPlayer;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Serializer\SerializerInterface;

#[AsEntityListener(event: Events::postUpdate, method: 'postUpdate', entity: MusicPlayer::class)]
class MusicPlayerListener
{

    private MessageBusInterface $bus;

    private SerializerInterface $serializer;

    public function __construct(MessageBusInterface $bus, SerializerInterface $serializer)
    {
        $this->bus = $bus;
        $this->serializer = $serializer;
    }

    /**
     * Send music player to client-side after modification
     *
     * @param MusicPlayer $musicPlayer
     * @return void
     */
    public function postUpdate(MusicPlayer $musicPlayer):void
    {
        $update = new Update('https://lescanardsmousquetaires.fr/musicplayer', $this->serializer->serialize($musicPlayer, 'json', ['groups' => 'musicPlayer:read']));
        $this->bus->dispatch($update);
    }

}