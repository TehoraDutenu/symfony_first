<?php

namespace App\Twig\Extension;

use App\Twig\Runtime\NavExtensionRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class NavExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/3.x/advanced.html#automatic-escaping
            new TwigFilter('filter_name', [NavExtensionRuntime::class, 'doSomething']),
        ];
    }

    public function getFunctions(): array
    {
        // - Créer une fonction qui va se charger de récupérer les consoles d'un jeu
        return [
            new TwigFunction('menu_items', [NavExtensionRuntime::class, 'menuItems']),
        ];
    }
}
