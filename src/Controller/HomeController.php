<?php

namespace App\Controller;

use App\Repository\GameRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    // - Déclarer la route
    #[Route('/', name: 'index')]

    public function index(GameRepository $gameRepository)
    {
        // - Déclarer des variables
        $title = 'Tous les jeux';

        // - Récupérer la liste des jeux
        $games = $gameRepository->findAll();

        // - Afficher la vue
        return $this->render('home/index.html.twig', [
            'title' => $title,
            'games' => $games
        ]);
    }

    // - Route pour détails
    #[Route('/detail/{id}', name: 'detail')]
    public function gameById(GameRepository $gameRepository, int $id)
    {
        // - Appel du repository
        $game = $gameRepository->getgameWithInfo($id);

        // - Appeler les consoles du jeu
        $consoles = $gameRepository->getConsolesByGame($id);

        return $this->render('home/detail.html.twig', [
            'game' => $game,
            'consoles' => $consoles
        ]);
    }

    // - Route pour les consoles
    #[Route('/console/{id}', name: 'console')]
    public function gamesByConsole(GameRepository $gameRepository, int $id)
    {
        // - Appel du repository
        $games = $gameRepository->getGamesByConsole($id);
        $title = "Tous les jeux " . $games[0]['label'];

        // - Afficher la vue
        return $this->render('home/index.html.twig', [
            'title' => $title,
            'games' => $games
        ]);
    }
}
