<?php

declare(strict_types=1);

namespace App\Application\Command\Author\CreateAuthor;

use App\Application\Model\Author\Author;
use App\Application\Model\Author\AuthorRepositoryInterface;

final class CreateAuthorCommandHandler
{
    private AuthorRepositoryInterface $authorRepository;

    public function __construct(AuthorRepositoryInterface $authorRepository)
    {
        $this->authorRepository = $authorRepository;
    }

    public function __invoke(CreateAuthorCommand $command): int
    {
        $author = new Author($command->getName());
        $this->authorRepository->add($author);

        return $author->getId();
    }
}
