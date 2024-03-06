<?php

declare(strict_types=1);

namespace RinyVT\DoctrineBlog\Repository;

use Doctrine\ORM\EntityManagerInterface;
use RinyVT\DoctrineBlog\Entity\Author;
use RinyVT\Typo3DoctrineOrm\Repository\AbstractRepository;

class AuthorRepository extends AbstractRepository
{
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, $em->getClassMetadata(Author::class));
    }
}
