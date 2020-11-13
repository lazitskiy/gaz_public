<?php

declare(strict_types=1);

namespace App\Application\Query\Book\GetBook;

use App\Application\Exception\ResourceNotFoundException;
use App\Application\Model\Book\BookRepositoryInterface;
use App\Application\Query\Book\DTO\BookDTO;
use App\Infrastructure\Repository\BookRepository;

final class GetBookQueryHandler
{
    private BookRepositoryInterface $bookRepository;

    public function __construct(BookRepository $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    public function __invoke(GetBookQuery $query): BookDTO
    {
        $book = $this->bookRepository->findOne($query->getId());

        if ($book === null) {
            throw new ResourceNotFoundException(sprintf('Book with id "%s" is not found', $query->getId()));
        }

        return BookDTO::fromEntity($book);
    }
}
