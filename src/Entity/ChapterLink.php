<?php

namespace App\Entity;

use App\Doctrine\Type\ChapterLinkResponseType;
use App\Repository\ChapterLinkRepository;
use App\ValueObject\ChapterLinkResponse;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChapterLinkRepository::class)]
class ChapterLink
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'Sources')]
    private ?Chapter $target = null;

    #[ORM\ManyToOne(inversedBy: 'chapterLinks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Chapter $Source = null;

    #[ORM\Column(type: ChapterLinkResponseType::NAME)]
    private ?ChapterLinkResponse $responses = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTarget(): ?Chapter
    {
        return $this->target;
    }

    public function setTarget(?Chapter $target): static
    {
        $this->target = $target;

        return $this;
    }

    public function getSource(): ?Chapter
    {
        return $this->Source;
    }

    public function setSource(?Chapter $Source): static
    {
        $this->Source = $Source;

        return $this;
    }

    public function getResponses(): ?ChapterLinkResponse
    {
        return $this->responses;
    }

    public function setResponses(ChapterLinkResponse $responses): static
    {
        $this->responses = $responses;

        return $this;
    }
}
