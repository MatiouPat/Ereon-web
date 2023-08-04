<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\DiceRepository;
use App\Repository\MapRepository;
use App\Repository\WorldRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Security $security, WorldRepository $worldRepository, DiceRepository $diceRepository, MapRepository $mapRepository, SerializerInterface $serializer): Response
    {
        /**
         * @var User
         */
        $user = $security->getUser();
        $dices = $diceRepository->findAll();
        $maps = $mapRepository->findAll();
        $worlds = $worldRepository->findWorldByUser($user->getId());
        return $this->render('home/index.html.twig', [
            'dices' => $serializer->serialize($dices, 'json', ['groups' => 'dice:read']),
            'maps' => $serializer->serialize($maps, 'json', ['groups' => 'map:read']),
            'worlds' => $serializer->serialize($worlds, 'json', ['groups' => 'world:read'])
        ]);
    }

}
