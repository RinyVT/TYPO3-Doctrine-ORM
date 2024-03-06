<?php

declare(strict_types=1);

namespace RinyVT\DoctrineBlog\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use RinyVT\Typo3DoctrineOrm\Entity\AbstractEntity;

#[ORM\Entity]
class Post extends AbstractEntity
{
    #[ORM\Column(type: 'string')]
    protected string $title = '';

    #[ORM\ManyToOne(targetEntity: Author::class)]
    #[ORM\JoinColumn(referencedColumnName: 'uid')]
    protected ?Author $author = null;

    #[ORM\Column(type: 'text')]
    protected string $content = '';

    #[ORM\OneToMany(mappedBy: 'post', targetEntity: Comment::class, cascade: ['persist'])]
    protected Collection $comments;

    #[ORM\ManyToMany(targetEntity: RelatedPage::class)]
    #[ORM\JoinTable(name: 'tx_doctrineblog_post_relatedpage_mm')]
    #[ORM\JoinColumn(name: 'uid_local', referencedColumnName: 'uid')]
    #[ORM\InverseJoinColumn(name: 'uid_foreign', referencedColumnName: 'uid')]
    protected Collection $relatedPages;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->relatedPages = new ArrayCollection();
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function getAuthor(): ?Author
    {
        return $this->author;
    }

    public function setAuthor(?Author $author): self
    {
        $this->author = $author;
        return $this;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): void
    {
        if ($this->comments->contains($comment)) {
            return;
        }
        $comment->setPost($this);
        $this->comments->add($comment);
    }

    public function getRelatedPages(): Collection
    {
        return $this->relatedPages;
    }
}
