<?php

namespace App\Contract;

interface CreatedUpdatedInterface
{
    public function autoSetCreatedAt(): void;
    public function autoSetUpdatedAt(): void;

    public function getCreatedAt(): ?\DateTimeImmutable;
    public function setCreatedAt(\DateTimeImmutable $createdAt): static;
    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static;
}
