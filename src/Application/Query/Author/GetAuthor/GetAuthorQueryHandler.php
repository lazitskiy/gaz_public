<?php

declare(strict_types=1);

namespace App\Application\Query\Author\GetAuthor;

use App\Application\Exception\ResourceNotFoundException;
use App\Application\Model\Author\AuthorRepositoryInterface;
use App\Application\Query\Author\DTO\AuthorDTO;

final class GetAuthorQueryHandler
{
    private AuthorRepositoryInterface $authorRepository;

    public function __construct(AuthorRepositoryInterface $authorRepository)
    {
        $this->authorRepository = $authorRepository;
    }

    public function __invoke(GetAuthorQuery $query): AuthorDTO
    {
        $author = $this->authorRepository->findOne($query->getId());

        if ($author === null) {
            throw new ResourceNotFoundException(sprintf('Author with id "%s" is not found', $query->getId()));
        }

        return AuthorDTO::fromEntity($author);
    }
}
