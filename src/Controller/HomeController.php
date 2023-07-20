<?php

namespace App\Controller;

use App\Repository\DiceRepository;
use App\Repository\MapRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(DiceRepository $diceRepository, MapRepository $mapRepository, SerializerInterface $serializer): Response
    {
        $dices = $diceRepository->findAll();
        $maps = $mapRepository->findAll();
        return $this->render('home/index.html.twig', [
            'dices' => $serializer->serialize($dices, 'json', ['groups' => 'dice:read']),
            'maps' => $serializer->serialize($maps, 'json')
        ]);
    }

}
