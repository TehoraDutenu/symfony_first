<?php

namespace App\Entity;

use App\Repository\NoteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NoteRepository::class)]
class Note
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $mediaNote = null;

    #[ORM\Column]
    private ?int $userNote = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMediaNote(): ?int
    {
        return $this->mediaNote;
    }

    public function setMediaNote(int $mediaNote): static
    {
        $this->mediaNote = $mediaNote;

        return $this;
    }

    public function getUserNote(): ?int
    {
        return $this->userNote;
    }

    public function setUserNote(int $userNote): static
    {
        $this->userNote = $userNote;

        return $this;
    }
}
