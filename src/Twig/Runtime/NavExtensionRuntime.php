<?php

namespace App\Twig\Runtime;

use App\Repository\GameRepository;
use Twig\Extension\RuntimeExtensionInterface;

class NavExtensionRuntime implements RuntimeExtensionInterface
{

    // - Déclarer une variable en private pour stocker notre instance
    private $gameRepository;

    public function __construct(GameRepository $gameRepository)
    {
        $this->gameRepository = $gameRepository;;
    }


    public function menuItems()
    {
        // - Appeller la méthode getCountGameByConsole()
        return $this->gameRepository->getCountGameByConsole();
    }

    public function ageItems()
    {
        return $this->gameRepository->getCountGameByAge();
    }

    public function filtersItems()
    {
        return [
            ['label' => 'Prix', 'filter' => 'g.price ASC', 'icon' => 'fa-sharp fa-solid fa-arrow-up'],
            ['label' => 'Prix', 'filter' => 'g.price DESC', 'icon' => 'fa-sharp fa-solid fa-arrow-down'],
            ['label' => 'Date de sortie', 'filter' => 'g.releaseDate ASC', 'icon' => 'fa-sharp fa-solid fa-arrow-up'],
            ['label' => 'Date de sortie', 'filter' => 'g.releaseDate DESC', 'icon' => 'fa-sharp fa-solid fa-arrow-down'],
            ['label' => 'Note presse', 'filter' => 'n.mediaNote ASC', 'icon' => 'fa-sharp fa-solid fa-arrow-up'],
            ['label' => 'Note presse', 'filter' => 'n.mediaNote DESC', 'icon' => 'fa-sharp fa-solid fa-arrow-down'],
            ['label' => 'Note utilisateur', 'filter' => 'n.userNote ASC', 'icon' => 'fa-sharp fa-solid fa-arrow-up'],
            ['label' => 'Note utilisateur', 'filter' => 'n.userNote DESC', 'icon' => 'fa-sharp fa-solid fa-arrow-down']
        ];
    }
}
