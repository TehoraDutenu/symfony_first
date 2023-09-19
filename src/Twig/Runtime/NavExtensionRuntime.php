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
}
