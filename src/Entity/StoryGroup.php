<?php

namespace App\Entity;

use App\Contract\CreatedUpdatedInterface;
use App\Enum\StoryGenreEnum;
use App\Enum\StoryStatus;
use App\Repository\StoryGroupRepository;
use App\Trait\CreatedUpdatedTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StoryGroupRepository::class)]
#[ORM\HasLifecycleCallbacks]
class StoryGroup implements CreatedUpdatedInterface
{
    use CreatedUpdatedTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\ManyToOne(inversedBy: 'storyGroups')]
    #[ORM\JoinColumn(nullable: false)]
    private ?UserProfile $owner = null;

    #[ORM\Column(type: 'string', nullable: true, enumType: StoryGenreEnum::class)]
    private ?StoryGenreEnum $genre = null;

    #[ORM\Column(length: 50, nullable: true, options: ['default' => 'default'])]
    private ?string $template = 'default';

    /**
     * @var Collection<int, Contributor>
     */
    #[ORM\OneToMany(targetEntity: Contributor::class, mappedBy: 'storyGroup', orphanRemoval: true)]
    private Collection $contributors;

    /**
     * @var Collection<int, Story>
     */
    #[ORM\OneToMany(targetEntity: Story::class, mappedBy: 'storyGroup', orphanRemoval: true)]
    private Collection $stories;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    public function __construct()
    {
        $this->contributors = new ArrayCollection();
        $this->stories = new ArrayCollection();
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

    public function getOwner(): ?UserProfile
    {
        return $this->owner;
    }

    public function setOwner(?UserProfile $owner): static
    {
        $this->owner = $owner;

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

    /**
     * @return Collection<int, Contributor>
     */
    public function getContributors(): Collection
    {
        return $this->contributors;
    }

    public function addContributor(Contributor $contributor): static
    {
        if (!$this->contributors->contains($contributor)) {
            $this->contributors->add($contributor);
            $contributor->setStoryGroup($this);
        }

        return $this;
    }

    public function removeContributor(Contributor $contributor): static
    {
        if ($this->contributors->removeElement($contributor)) {
            // set the owning side to null (unless already changed)
            if ($contributor->getStoryGroup() === $this) {
                $contributor->setStoryGroup(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Story>
     */
    public function getStories(): Collection
    {
        return $this->stories;
    }

    public function addStory(Story $story): static
    {
        if (!$this->stories->contains($story)) {
            $this->stories->add($story);
            $story->setStoryGroup($this);
        }

        return $this;
    }

    public function removeStory(Story $story): static
    {
        if ($this->stories->removeElement($story)) {
            // set the owning side to null (unless already changed)
            if ($story->getStoryGroup() === $this) {
                $story->setStoryGroup(null);
            }
        }

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

    public function getLastStory(): ?Story
    {
        return $this->stories->last();
    }

    public function isPublished(): bool
    {
        foreach ($this->getStories() as $story) {
            if ($story->getStoryStatus() === StoryStatus::PUBLISHED) {
                return true;
            }
        }

        return false;
    }
}
