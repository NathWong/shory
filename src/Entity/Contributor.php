<?php

namespace App\Entity;

use App\Repository\ContributorRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContributorRepository::class)]
class Contributor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'contributions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?UserProfile $UserProfile = null;

    #[ORM\ManyToOne(inversedBy: 'contributors')]
    #[ORM\JoinColumn(nullable: false)]
    private ?StoryGroup $storyGroup = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $role = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserProfile(): ?UserProfile
    {
        return $this->UserProfile;
    }

    public function setUserProfile(?UserProfile $UserProfile): static
    {
        $this->UserProfile = $UserProfile;

        return $this;
    }

    public function getStoryGroup(): ?StoryGroup
    {
        return $this->storyGroup;
    }

    public function setStoryGroup(?StoryGroup $storyGroup): static
    {
        $this->storyGroup = $storyGroup;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(?string $role): static
    {
        $this->role = $role;

        return $this;
    }
}
