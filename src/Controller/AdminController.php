<?php

namespace App\Controller;

use App\Entity\Game;
use App\Repository\GameRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin')]
class AdminController extends AbstractController
{
    #[Route('/dashboard', name: 'app_admin')]
    public function index(GameRepository $gameRepository): Response
    {
        return $this->render('game/index.html.twig', [
            'games' => $gameRepository->findAll()
        ]);
    }

    #[Route('/detail/{id}', name: 'app_game_show')]
    public function gameDetailDashboard(GameRepository $gameRepository, int $id)
    {
        $game = $gameRepository->getgameWithInfo($id);
        $consoles = $gameRepository->getConsolesByGame($id);

        return $this->render('game/show.html.twig', [
            'game' => $game,
            'consoles' => $consoles
        ]);
    }

    // - Supprimer un jeu
    #[Route('/delete/{id}', name: 'app_game_delete')]
    public function delete(Request $request, Game $game, GameRepository $gameRepository)
    {
        if ($this->isCsrfTokenValid('delete', $game->getId(), $request->request->get('_token'))) {
            $gameRepository->delete($game, true);
        }
        return $this->redirectToRoute('app_admin', [], Response::HTTP_SEE_OTHER);
    }
}
