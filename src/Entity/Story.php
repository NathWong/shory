<?php

namespace App\Entity;

use App\Contract\CreatedUpdatedInterface;
use App\Enum\StoryGenreEnum;
use App\Enum\StoryStatus;
use App\Repository\StoryRepository;
use App\Trait\CreatedUpdatedTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: StoryRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Story implements CreatedUpdatedInterface
{
    use CreatedUpdatedTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $summary = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(type: 'string', nullable: true, enumType: StoryStatus::class)]
    private ?StoryStatus $storyStatus = null;

    /**
     * @var Collection<int, Chapter>
     */
    #[ORM\OneToMany(targetEntity: Chapter::class, mappedBy: 'story', orphanRemoval: true)]
    private Collection $chapters;

    #[ORM\Column(type: 'string', nullable: true, enumType: StoryGenreEnum::class)]
    private ?StoryGenreEnum $genre = null;

    #[ORM\Column(length: 50, nullable: true, options: ['default' => 'default'])]
    private ?string $template = 'default';

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Chapter $beginning = null;

    #[ORM\ManyToOne(inversedBy: 'stories')]
    #[ORM\JoinColumn(nullable: false)]
    private ?StoryGroup $storyGroup = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $publishedAt = null;

    public function __construct()
    {
        $this->chapters = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(?string $summary): static
    {
        $this->summary = $summary;

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

    public function getStoryStatus(): ?StoryStatus
    {
        return $this->storyStatus;
    }

    public function setStoryStatus(StoryStatus $storyStatus): static
    {
        $this->storyStatus = $storyStatus;

        return $this;
    }

    /**
     * @return Collection<int, Chapter>
     */
    public function getChapters(): Collection
    {
        return $this->chapters;
    }

    public function addChapter(Chapter $chapter): static
    {
        if (!$this->chapters->contains($chapter)) {
            $this->chapters->add($chapter);
            $chapter->setStory($this);
        }

        return $this;
    }

    public function removeChapter(Chapter $chapter): static
    {
        if ($this->chapters->removeElement($chapter)) {
            // set the owning side to null (unless already changed)
            if ($chapter->getStory() === $this) {
                $chapter->setStory(null);
            }
        }

        return $this;
    }

    public function getGenre(): ?StoryGenreEnum
    {
        return $this->genre;
    }

    public function setGenre(?StoryGenreEnum $genre): static
    {
        $this->genre = $genre;

        return $this;
    }

    public function getTemplate(): ?string
    {
        return $this->template;
    }

    public function setTemplate(?string $template): static
    {
        $this->template = $template;

        return $this;
    }

    public function getBeginning(): ?Chapter
    {
        return $this->beginning;
    }

    public function setBeginning(?Chapter $beginning): static
    {
        $this->beginning = $beginning;

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

    public function getPublishedAt(): ?\DateTimeImmutable
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(?\DateTimeImmutable $publishedAt): static
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }
}
