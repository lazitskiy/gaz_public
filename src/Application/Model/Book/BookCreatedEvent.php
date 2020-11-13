<?php

declare(strict_types=1);

namespace App\Application\Model\Book;

use App\Application\Model\DomainEventInterface;

final class BookCreatedEvent implements DomainEventInterface
{
    private Book $book;

    public function __construct(Book $book)
    {
        $this->book = $book;
    }

    public function getBook(): Book
    {
        return $this->book;
    }
}
