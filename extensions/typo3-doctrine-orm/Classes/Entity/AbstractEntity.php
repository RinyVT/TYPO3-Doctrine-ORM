<?php

declare(strict_types=1);

namespace RinyVT\Typo3DoctrineOrm\Entity;

use Doctrine\ORM\Mapping as ORM;

abstract class AbstractEntity implements EntityInterface
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer', options: ['unsigned' => true])]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    protected int $uid = 0;

    #[ORM\Column(type: 'integer', options: ['default' => 0])]
    protected int $pid = 0;

    public function getUid(): int
    {
        return $this->uid;
    }

    public function getPid(): int
    {
        return $this->pid;
    }
}
