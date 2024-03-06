<?php

declare(strict_types=1);

namespace RinyVT\Typo3DoctrineOrm\Repository;

use Doctrine\ORM\EntityRepository;
use RinyVT\Typo3DoctrineOrm\Entity\AbstractEntity;

abstract class AbstractRepository extends EntityRepository implements EntityRepositoryInterface
{
    public function update(AbstractEntity $entity): void
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();
    }
}
