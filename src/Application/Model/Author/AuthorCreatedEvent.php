<?php

declare(strict_types=1);

namespace App\Application\Model\Author;

use App\Application\Model\DomainEventInterface;

final class AuthorCreatedEvent implements DomainEventInterface
{
    private Author $author;

    public function __construct(Author $author)
    {
        $this->author = $author;
    }

    public function getAuthor(): Author
    {
        return $this->author;
    }
}
