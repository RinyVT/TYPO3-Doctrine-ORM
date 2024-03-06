<?php

declare(strict_types=1);

namespace RinyVT\DoctrineBlog\Controller;

use Psr\Http\Message\ResponseInterface;
use RinyVT\DoctrineBlog\Entity\Comment;
use RinyVT\DoctrineBlog\Entity\Post;
use RinyVT\DoctrineBlog\Repository\PostRepository;
use TYPO3\CMS\Core\Http\RedirectResponse;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class PostController extends ActionController
{
    public function __construct(
        protected PostRepository $postRepository
    ) {
    }

    public function listAction(): ResponseInterface
    {
        $this->view->assign('posts', $this->postRepository->findAll());
        return $this->htmlResponse();
    }

    public function showAction(Post $post): ResponseInterface
    {
        $this->view->assign('post', $post);
        return $this->htmlResponse();
    }

    public function addCommentAction(Post $post, Comment $newComment): RedirectResponse
    {
        $post->addComment($newComment);
        $this->postRepository->update($post);
        return new RedirectResponse(
            $this->uriBuilder->uriFor('show', ['post' => $post->getUid()])
        );
    }
}
