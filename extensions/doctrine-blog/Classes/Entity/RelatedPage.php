<?php

declare(strict_types=1);

namespace RinyVT\DoctrineBlog\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use RinyVT\Typo3DoctrineOrm\Entity\AbstractEntity;
use RinyVT\Typo3DoctrineOrm\Mapping as Typo3DoctrineORM;

#[ORM\Entity]
#[Typo3DoctrineORM\ExtendTable(name: 'pages')]
class RelatedPage extends AbstractEntity
{
    #[ORM\Column(type: 'string', options: ['default' => ''])]
    protected string $title = '';

    #[ORM\ManyToMany(targetEntity: Post::class, mappedBy: 'relatedPages')]
    protected Collection $relatedPosts;

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getRelatedPosts(): Collection
    {
        return $this->relatedPosts;
    }
}
