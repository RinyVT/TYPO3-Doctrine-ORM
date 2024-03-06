<?php

declare(strict_types=1);

namespace RinyVT\DoctrineBlog\Repository;

use Doctrine\ORM\EntityManagerInterface;
use RinyVT\DoctrineBlog\Entity\Post;
use RinyVT\Typo3DoctrineOrm\Repository\AbstractRepository;

class PostRepository extends AbstractRepository
{
    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct($em, $em->getClassMetadata(Post::class));
    }
}
