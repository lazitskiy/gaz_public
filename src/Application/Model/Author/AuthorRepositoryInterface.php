<?php

declare(strict_types=1);

namespace App\Application\Model\Author;

interface AuthorRepositoryInterface
{
    public function findOne(int $id): ?Author;

    /**
     * @param array<int> $ids
     * @return Author[]
     */
    public function findMany(array $ids): array;

    public function add(Author $author): void;
}
