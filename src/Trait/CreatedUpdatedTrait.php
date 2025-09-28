<?php

namespace App\Trait;

use Doctrine\ORM\Mapping as ORM;

/**
 * @method setUpdatedAt
 * @method setCreatedAt
 * @method getCreatedAt
 */
trait CreatedUpdatedTrait
{
    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function autoSetUpdatedAt(): void
    {
        $this->setUpdatedAt(new \DateTimeImmutable());
    }

    #[ORM\PrePersist]
    public function autoSetCreatedAt(): void
    {
        if (!$this->getCreatedAt()) {
            $this->setCreatedAt(new \DateTimeImmutable());
        }
    }
}
