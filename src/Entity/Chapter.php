<?php

namespace App\Entity;

use App\Contract\CreatedUpdatedInterface;
use App\Repository\ChapterRepository;
use App\Trait\CreatedUpdatedTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChapterRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Chapter implements CreatedUpdatedInterface
{
    use CreatedUpdatedTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $content = null;

    #[ORM\ManyToOne(inversedBy: 'chapters')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Story $story = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $linkType = null;

    /**
     * @var Collection<int, ChapterLink>
     */
    #[ORM\OneToMany(targetEntity: ChapterLink::class, mappedBy: 'target')]
    private Collection $Sources;

    /**
     * @var Collection<int, ChapterLink>
     */
    #[ORM\OneToMany(targetEntity: ChapterLink::class, mappedBy: 'Source', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $chapterLinks;

    public function __construct()
    {
        $this->Sources = new ArrayCollection();
        $this->chapterLinks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): static
    {
        $this->content = $content;

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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getLinkType(): ?string
    {
        return $this->linkType;
    }

    public function setLinkType(?string $linkType): static
    {
        $this->linkType = $linkType;

        return $this;
    }

    /**
     * @return Collection<int, ChapterLink>
     */
    public function getSources(): Collection
    {
        return $this->Sources;
    }

    public function addSource(ChapterLink $source): static
    {
        if (!$this->Sources->contains($source)) {
            $this->Sources->add($source);
            $source->setTarget($this);
        }

        return $this;
    }

    public function removeSource(ChapterLink $source): static
    {
        if ($this->Sources->removeElement($source)) {
            // set the owning side to null (unless already changed)
            if ($source->getTarget() === $this) {
                $source->setTarget(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ChapterLink>
     */
    public function getChapterLinks(): Collection
    {
        return $this->chapterLinks;
    }

    public function addChapterLink(ChapterLink $chapterLink): static
    {
        if (!$this->chapterLinks->contains($chapterLink)) {
            $this->chapterLinks->add($chapterLink);
            $chapterLink->setSource($this);
        }

        return $this;
    }

    public function removeChapterLink(ChapterLink $chapterLink): static
    {
        if ($this->chapterLinks->removeElement($chapterLink)) {
            // set the owning side to null (unless already changed)
            if ($chapterLink->getSource() === $this) {
                $chapterLink->setSource(null);
            }
        }

        return $this;
    }
}
