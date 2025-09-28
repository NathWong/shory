<?php

namespace App\Entity;

use App\Repository\UserProfileRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserProfileRepository::class)]
class UserProfile
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    private ?string $lastName = null;

    #[ORM\Column(length: 255)]
    private ?string $penName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $avatar = null;

    #[ORM\Column]
    private int $rank = 1;

    #[ORM\OneToOne(inversedBy: 'userProfile', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $account = null;

    /**
     * @var Collection<int, Story>
     */
    #[ORM\OneToMany(targetEntity: Story::class, mappedBy: 'Owner')]
    private Collection $stories;

    /**
     * @var Collection<int, ReadingHistory>
     */
    #[ORM\OneToMany(targetEntity: ReadingHistory::class, mappedBy: 'userProfile', orphanRemoval: true)]
    private Collection $readingHistories;

    /**
     * @var Collection<int, StoryGroup>
     */
    #[ORM\OneToMany(targetEntity: StoryGroup::class, mappedBy: 'owner')]
    private Collection $storyGroups;

    /**
     * @var Collection<int, Contributor>
     */
    #[ORM\OneToMany(targetEntity: Contributor::class, mappedBy: 'UserProfile', orphanRemoval: true)]
    private Collection $contributions;

    public function __construct()
    {
        $this->stories = new ArrayCollection();
        $this->contributions = new ArrayCollection();
        $this->readingHistories = new ArrayCollection();
        $this->storyGroups = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getPenName(): ?string
    {
        return $this->penName;
    }

    public function setPenName(string $penName): static
    {
        $this->penName = $penName;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): static
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getRank(): ?int
    {
        return $this->rank;
    }

    public function setRank(int $rank): static
    {
        $this->rank = $rank;

        return $this;
    }

    public function getAccount(): ?User
    {
        return $this->account;
    }

    public function setAccount(User $account): static
    {
        $this->account = $account;

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
            $story->setOwner($this);
        }

        return $this;
    }

    public function removeStory(Story $story): static
    {
        if ($this->stories->removeElement($story)) {
            // set the owning side to null (unless already changed)
            if ($story->getOwner() === $this) {
                $story->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Story>
     */
    public function getContributions(): Collection
    {
        return $this->contributions;
    }

    public function addContribution(Story $contribution): static
    {
        if (!$this->contributions->contains($contribution)) {
            $this->contributions->add($contribution);
            $contribution->addContributor($this);
        }

        return $this;
    }

    public function removeContribution(Story $contribution): static
    {
        if ($this->contributions->removeElement($contribution)) {
            $contribution->removeContributor($this);
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->getPenName() ?: $this->getFirstName() ?: '';
    }

    /**
     * @return Collection<int, ReadingHistory>
     */
    public function getReadingHistories(): Collection
    {
        return $this->readingHistories;
    }

    public function addReadingHistory(ReadingHistory $readingHistory): static
    {
        if (!$this->readingHistories->contains($readingHistory)) {
            $this->readingHistories->add($readingHistory);
            $readingHistory->setUserProfile($this);
        }

        return $this;
    }

    public function removeReadingHistory(ReadingHistory $readingHistory): static
    {
        if ($this->readingHistories->removeElement($readingHistory)) {
            // set the owning side to null (unless already changed)
            if ($readingHistory->getUserProfile() === $this) {
                $readingHistory->setUserProfile(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, StoryGroup>
     */
    public function getStoryGroups(): Collection
    {
        return $this->storyGroups;
    }

    public function addStoryGroup(StoryGroup $storyGroup): static
    {
        if (!$this->storyGroups->contains($storyGroup)) {
            $this->storyGroups->add($storyGroup);
            $storyGroup->setOwner($this);
        }

        return $this;
    }

    public function removeStoryGroup(StoryGroup $storyGroup): static
    {
        if ($this->storyGroups->removeElement($storyGroup)) {
            // set the owning side to null (unless already changed)
            if ($storyGroup->getOwner() === $this) {
                $storyGroup->setOwner(null);
            }
        }

        return $this;
    }
}
