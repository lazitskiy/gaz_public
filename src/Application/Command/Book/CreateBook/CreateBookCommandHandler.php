<?php

declare(strict_types=1);

namespace App\Application\Command\Book\CreateBook;

use App\Application\Model\Author\AuthorRepositoryInterface;
use App\Application\Model\Book\Book;
use App\Application\Model\Book\BookRepositoryInterface;

final class CreateBookCommandHandler
{
    private BookRepositoryInterface $bookRepository;

    private AuthorRepositoryInterface $authorRepository;

    public function __construct(BookRepositoryInterface $bookRepository, AuthorRepositoryInterface $authorRepository)
    {
        $this->bookRepository = $bookRepository;
        $this->authorRepository = $authorRepository;
    }

    public function __invoke(CreateBookCommand $command): int
    {
        $authors = $this->authorRepository->findMany($command->getAuthorsIds());
        $book = new Book($command->getNameRu(), $command->getNameEn(), $authors);
        $this->bookRepository->add($book);

        return $book->getId();
    }
}
