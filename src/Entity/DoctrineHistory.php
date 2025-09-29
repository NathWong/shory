<?php

namespace App\Entity;

use App\Repository\DoctrineHistoryRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bundle\SecurityBundle\Security;

#[ORM\Entity(repositoryClass: DoctrineHistoryRepository::class)]
#[ORM\HasLifecycleCallbacks]
class DoctrineHistory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $objectClass = null;

    #[ORM\Column]
    private ?int $ObjectId = null;

    #[ORM\Column(type: Types::JSON)]
    private array $change = [];

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne]
    private ?User $createdBy = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getObjectClass(): ?string
    {
        return $this->objectClass;
    }

    public function setObjectClass(string $objectClass): static
    {
        $this->objectClass = $objectClass;

        return $this;
    }

    public function getObjectId(): ?int
    {
        return $this->ObjectId;
    }

    public function setObjectId(int $ObjectId): static
    {
        $this->ObjectId = $ObjectId;

        return $this;
    }

    public function getChange(): array
    {
        return $this->change;
    }

    public function setChange(array $change): static
    {
        $this->change = $change;

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

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): static
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    #[ORM\PrePersist]
    public function autoSetCreatedAt(): void
    {
        if (!$this->getCreatedAt()) {
            $this->setCreatedAt(new \DateTimeImmutable());
        }
    }

    #[ORM\PrePersist]
    public function autoSetCreatedBy(Security $security): void
    {
        if ($this->getCreatedBy()) {
            return;
        }

        $user = $security->getUser();
        if (!($user instanceof User)) {
            return;
        }

        $this->setCreatedBy($user);
    }
}
