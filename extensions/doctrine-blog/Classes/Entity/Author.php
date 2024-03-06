<?php

declare(strict_types=1);

namespace RinyVT\DoctrineBlog\Entity;

use Doctrine\ORM\Mapping as ORM;
use RinyVT\Typo3DoctrineOrm\Entity\AbstractEntity;

#[ORM\Entity]
class Author extends AbstractEntity
{
    #[ORM\Column(type: 'string')]
    protected string $firstName = '';

    #[ORM\Column(type: 'string')]
    protected string $lastName = '';

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;
        return $this;
    }
}
