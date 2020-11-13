<?php

declare(strict_types=1);

namespace App\Application\Query\Book\DTO;

use App\Application\Model\Book\Book;
use App\Application\Query\Author\DTO\AuthorDTO;

final class BookDTO
{
    private int $id;

    private string $name;

    /**
     * @var AuthorDTO[]
     */
    private array $authors = [];

    /**
     * @var AuthorDTO[]
     */
    private array $authorsToArray = [];

    public static function fromEntity(Book $book): BookDTO
    {
        $dto = new static();
        $dto->setId($book->getId());
        $dto->setName($book->getName());

        foreach ($book->getAuthors() as $author) {
            $dto->addAuthor(AuthorDTO::fromEntity($author));
        }

        return $dto;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return AuthorDTO[]
     */
    public function getAuthors(): array
    {
        return $this->authors;
    }

    public function getAuthorsToArray()
    {
        return array_map(function (AuthorDTO $authorDTO) {
            return [
                'Id' => $authorDTO->getId(),
                'Name' => $authorDTO->getName(),
            ];
        }, $this->getAuthors());
    }

    public function addAuthor($authorDTO)
    {
        $this->authors[] = $authorDTO;
    }
}
