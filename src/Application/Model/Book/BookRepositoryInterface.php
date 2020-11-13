<?php

declare(strict_types=1);

namespace App\Application\Model\Book;

interface BookRepositoryInterface
{
    public function findOne(int $id): ?Book;

    /**
     * @param array<int> $ids
     * @return Book[]
     */
    public function findMany(array $ids): array;

    public function add(Book $book): void;
}
