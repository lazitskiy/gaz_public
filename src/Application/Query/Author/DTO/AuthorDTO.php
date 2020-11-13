<?php

declare(strict_types=1);

namespace App\Application\Query\Author\DTO;

use App\Application\Model\Author\Author;

final class AuthorDTO
{
    private int $id;

    private string $name;

    public static function fromEntity(Author $author): AuthorDTO
    {
        $dto = new static();
        $dto->setId($author->getId());
        $dto->setName($author->getName());

        return $dto;
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return AuthorDTO
     */
    public static function fromQueryArray(array $data): AuthorDTO
    {
        if (!isset($data['id'], $data['name'])) {
            throw new \InvalidArgumentException(sprintf('Not all keys are set or null %s', var_export($data, true)));
        }

        $dto = new static();
        $dto->setId((int)$data['id']);
        $dto->setName($data['name']);

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
}
