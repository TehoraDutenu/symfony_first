<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\Note;
use App\Form\GameType;
use App\Repository\GameRepository;
use App\Repository\NoteRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/admin')]
class AdminController extends AbstractController
{
    // - Afficher la liste des jeux
    #[Route('/dashboard', name: 'app_admin')]
    public function index(GameRepository $gameRepository): Response
    {
        return $this->render('game/index.html.twig', [
            'games' => $gameRepository->findAll()
        ]);
    }

    // - Afficher le détail d'un jeu
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
        if ($this->isCsrfTokenValid('delete' . $game->getId(), $request->request->get('_token'))) {
            $gameRepository->delete($game, true);
        }
        return $this->redirectToRoute('app_admin', [], Response::HTTP_SEE_OTHER);
    }

    // - Ajouter un jeu
    #[Route('/new', name: 'app_game_new', methods: ['GET', 'POST'])]
    public function new(Request $request, GameRepository $gameRepository, NoteRepository $noteRepository)
    {
        $game = new Game();
        $form = $this->createForm(GameType::class, $game);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // - Gérer l'image uploadée
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                // - Récupérer le nom d'origine de l'image
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                // - Générer un nouveau nom unique pour éviter d'écraser des images de même nom
                $newFilename = $originalFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                try {
                    // - Déplacer l'image uploadée dans le dossier public/image
                    $imageFile->move(
                        // - game_images_directory est configuré dans config/services.yaml
                        $this->getParameter('game_images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('danger', 'Une erreur est survenue lors de l\'upload de l\'image');
                }

                // - Donner un nouveau nom pour la BDD
                $game->setImagePath($newFilename);
            }

            // - Récupérer les notes pour le jeu
            $userNote = $form->get('note')->get('userNote')->getData();
            $mediaNote = $form->get('note')->get('mediaNote')->getData();

            // - Créer une nouvelle ligne dans notre BDD, ajouter les notes
            $note = new Note();
            $note->setUserNote($userNote);
            $note->setMediaNote($mediaNote);

            // On enregistre la note dans la bdd
            $noteRepository->save($note, true);

            // - Récupérer l'id de la note pour la donner au jeu
            $game->setNote($noteRepository->find($note->getId()));

            // - Enregistrer le jeu dans la Bdd
            $gameRepository->save($game, true);

            return $this->redirectToRoute('app_admin', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('game/new.html.twig', [
            'game' => $game,
            'form' => $form
        ]);
    }

    // - Modifier un jeu
    #[Route('/edit/{id}', name: 'app_game_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Game $game, GameRepository $gameRepository)
    {
        $form = $this->createForm(GameType::class, $game);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $gameRepository->save($game, true);

            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                // - Récupérer le nom d'origine de l'image
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                // - Générer un nouveau nom unique pour éviter d'écraser des images de même nom
                $newFilename = $originalFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                try {
                    // - Déplacer l'image uploadée dans le dossier public/image
                    $imageFile->move(
                        // - game_images_directory est configuré dans config/services.yaml
                        $this->getParameter('game_images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('danger', 'Une erreur est survenue lors de l\'upload de l\'image');
                }

                // - Donner un nouveau nom pour la BDD
                $game->setImagePath($newFilename);
            }

            // - Enregistrer le jeu dans la Bdd
            $gameRepository->save($game, true);

            return $this->redirectToRoute('app_admin', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('game/edit.html.twig', [
            'game' => $game,
            'form' => $form
        ]);
    }
}
