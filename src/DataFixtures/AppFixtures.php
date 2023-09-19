<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Age;
use App\Entity\Game;
use App\Entity\Note;
use App\Entity\User;
use Faker\Generator;
use App\Entity\Console;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    // - Encoder le mot de passe
    private $encoder;

    // - Propriété pour le faker
    private \Faker\Generator $faker;


    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
        $this->faker = Factory::create();
    }

    public function load(ObjectManager $manager): void
    {
        // - Récupérer la méthode loadAge
        $this->loadAge($manager);
        // - Récupérer la méthode loadUser
        $this->loadUser($manager);
        // - Récupérer la méthode loadConsole
        $this->loadConsole($manager);
        // - Récupérer la méthode loadNote
        $this->loadNote($manager);
        // - Récupérer la méthode loadGame
        $this->loadGame($manager);

        $manager->flush();
    }

    // - Générer des utilisateurs
    public function loadUser(ObjectManager $manager): void
    {
        // - /!\ l'ordre des load est importante !!

        // - Instancier User
        $user = new User();
        // - Seter les valeurs pour créer un utilisateur
        $user->setEmail('admin@admin.com')
            ->setPassword($this->encoder->hashPassword($user, 'admin'))
            ->setRoles(['ROLE_ADMIN']);

        // - Persister les données (rôle de préparation)
        $manager->persist($user);
    }

    // - Créer nos consoles
    public function loadConsole(ObjectManager $manager): void
    {
        // - Déclarer tableau de consoles
        $consoleArray = ['PS4', 'PS5', '360', 'XboxSeries', 'ONE', 'Switch', 'PC'];

        // - Boucler sur le tableau et donner des valeurs
        foreach ($consoleArray as $key => $cons) {
            $console = new Console();
            $console->setLabel($cons);
            $manager->persist($console);

            // - Définir une référence pour pouvoir faires nos relations avec console
            $this->addReference('console_' . $key + 1, $console);
        }
    }

    // - Créer les âges
    public function loadAge(ObjectManager $manager): void
    {
        $ageArray = [
            ['key' => 1, 'label' => '3', 'imagePath' => 'pegi3.png'],
            ['key' => 2, 'label' => '7', 'imagePath' => 'pegi7.png'],
            ['key' => 3, 'label' => '12', 'imagePath' => 'pegi12.png'],
            ['key' => 4, 'label' => '16', 'imagePath' => 'pegi16.png'],
            ['key' => 5, 'label' => '18', 'imagePath' => 'pegi18.png']
        ];

        // - Boucler sur le tableau et donner des valeurs
        foreach ($ageArray as $key => $value) {
            $age = new Age();
            $age->setLabel($value['label']);
            $age->setImagePath($value['imagePath']);
            $manager->persist($age);

            // - Définir une référence pour pouvoir faires nos relations avec age
            $this->addReference('age_' . $value['key'], $age);
        }
    }

    // - Créer les notes
    public function loadNote(ObjectManager $manager): void
    {
        // - Créer une boucle for pour générer des notres entre 10 et 20
        for ($i = 1; $i <= 15; $i++) {
            $note = new Note();
            $note->setMediaNote(rand(10, 20));
            $note->setUserNote(rand(10, 20));

            $manager->persist($note);

            $this->addReference('note_' . $i, $note);
        }
    }

    public function loadGame(ObjectManager $manager): void
    {
        $gameArray = [
            ["note" => 1, "age" => 1, "title" => "Animal Crossing : New Horizons", "imagePath" => "animal-crossing.jpg", "console" => [6]],
            ["note" => 2, "age" => 5, "title" => "Call of Duty : Modern Warfare 2", "imagePath" => "call-of-duty.jpg", "console" => [1, 2, 4, 5, 7]],
            ["note" => 3, "age" => 1, "title" => "Fall Guys : Ultimate Knockout", "imagePath" => "fall-guys.jpg", "console" => [1, 2, 4, 5, 7]],
            ["note" => 4, "age" => 1, "title" => "FIFA 23", "imagePath" => "fifa-23.jpg", "console" => [1, 2, 4, 5, 7]],
            ["note" => 5, "age" => 5, "title" => "Grand Theft Auto V", "imagePath" => "gta-v.jpg", "console" => [1, 2, 3, 4, 5, 7]],
            ["note" => 6, "age" => 2, "title" => "Human Fall Flat", "imagePath" => "Human-Fall-Flat.jpg", "console" => [1, 2, 4, 5, 7]],
            ["note" => 7, "age" => 1, "title" => "Mario Kart 8 Deluxe", "imagePath" => "mario-kart-8.jpg", "console" => [6]],
            ["note" => 8, "age" => 1, "title" => "Super Mario Odyssey", "imagePath" => "mario-odyssey.jpg", "console" => [6]],
            ["note" => 9, "age" => 2, "title" => "Minecraft", "imagePath" => "minecraft.jpg", "console" => [1, 2, 3, 4, 5, 7]],
            ["note" => 10, "age" => 2, "title" => "Légendes Pokémon: Arceus", "imagePath" => "pokemon.jpg", "console" => [6]],
            ["note" => 11, "age" => 4, "title" => "PlayerUnknown's Battlegrounds", "imagePath" => "PUBG-Battlegrounds.jpg", "console" => [1, 5, 7]],
            ["note" => 12, "age" => 5, "title" => "Red Dead Redemption II", "imagePath" => "red-dead-redemption.jpg", "console" => [1, 5, 7]],
            ["note" => 13, "age" => 5, "title" => "The Elder Scrolls V : Skyrim", "imagePath" => "The-Elder-Scrolls-Skyrim.jpg", "console" => [1, 2, 3, 4, 5, 7]],
            ["note" => 14, "age" => 3, "title" => "The Legend of Zelda : Breath of the Wild", "imagePath" => "zelda.jpg", "console" => [6]],
        ];

        foreach ($gameArray as $value) {
            $game = new Game();
            $game->setTitle($value['title']);
            // - Fausse data de description
            $game->setDescription(implode(',', $this->faker->words(10)));
            $game->setImagePath($value['imagePath']);
            // - Fausse data en timestamp
            $game->setReleaseDate($this->faker->dateTimeBetween('-10 years')->getTimestamp());
            $game->setPrice(rand(0, 6000));

            // - Appeller les références pour effectuer les relations
            $game->setNote($this->getReference('note_' . $value['note']));
            $game->setAge($this->getReference('age_' . $value['age']));

            // - Boucler sur les consoles
            foreach ($value['console'] as $console) {
                $game->addConsole($this->getReference('console_' . $console));
            }
            $manager->persist($game);
        }
    }
}
