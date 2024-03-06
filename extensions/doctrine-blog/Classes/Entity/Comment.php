<?php

declare(strict_types=1);

namespace RinyVT\DoctrineBlog\Entity;

use Doctrine\ORM\Mapping as ORM;
use RinyVT\Typo3DoctrineOrm\Entity\AbstractEntity;

#[ORM\Entity]
class Comment extends AbstractEntity
{
    #[ORM\Column(type: 'string')]
    protected string $name = '';

    #[ORM\Column(type: 'text')]
    protected string $content = '';

    #[ORM\ManyToOne(targetEntity: Post::class)]
    #[ORM\JoinColumn(referencedColumnName: 'uid')]
    protected ?Post $post = null;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(?Post $post): void
    {
        $this->post = $post;
    }
}
