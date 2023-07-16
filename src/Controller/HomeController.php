<?php

namespace App\Controller;

use App\Repository\DiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(DiceRepository $diceRepository, SerializerInterface $serializer): Response
    {
        $dices = $diceRepository->findAll();
        return $this->render('home/index.html.twig', [
            'dices' => $serializer->serialize($dices, 'json', ['groups' => 'dice:read'])
        ]);
    }

}
