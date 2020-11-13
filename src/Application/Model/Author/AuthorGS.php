<?php

declare(strict_types=1);

namespace App\Application\Model\Author;

use App\Application\Model\Book\Book;
use App\Infrastructure\Service\Assert\Assert;
use Doctrine\ORM\PersistentCollection;

trait AuthorGS
{
    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    private function setName(string $name): void
    {
        Assert::minLength($name, static::MIN_NAME_LENGTH, 'Name should contain at least %2$s characters. Got: %s');
        Assert::maxLength($name, static::MAX_NAME_LENGTH, 'Name should contain at most %2$s characters. Got: %s');
        $this->name = $name;
    }

    public function addBook(Book $book): void
    {
        $this->books[] = $book;
    }

    public function getBooks(): PersistentCollection
    {
        return $this->books;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    private function setCreatedAt(\DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}
