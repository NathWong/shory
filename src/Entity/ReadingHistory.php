<?php

namespace App\Entity;

use App\Repository\ReadingHistoryRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReadingHistoryRepository::class)]
#[ORM\HasLifecycleCallbacks]
class ReadingHistory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'readingHistories')]
    #[ORM\JoinColumn(nullable: false)]
    private ?UserProfile $userProfile = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Story $story = null;

    #[ORM\ManyToOne]
    private ?ChapterLink $chapterLink = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserProfile(): ?UserProfile
    {
        return $this->userProfile;
    }

    public function setUserProfile(?UserProfile $userProfile): static
    {
        $this->userProfile = $userProfile;

        return $this;
    }

    public function getStory(): ?Story
    {
        return $this->story;
    }

    public function setStory(?Story $story): static
    {
        $this->story = $story;

        return $this;
    }

    public function getChapterLink(): ?ChapterLink
    {
        return $this->chapterLink;
    }

    public function setChapterLink(?ChapterLink $chapterLink): static
    {
        $this->chapterLink = $chapterLink;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    #[ORM\PrePersist]
    public function initializeCreatedAt(): void
    {
        $this->setCreatedAt(new \DateTimeImmutable());
    }
}
