<?php

declare(strict_types=1);

namespace App\Application\Command\Book;

abstract class BookCommand
{
    protected string $nameRu;
    protected string $nameEn;
    protected array $authorsIds;

    public function __construct(string $nameRu, string $nameEn, array $authorsIds)
    {
        $this->nameRu = $nameRu;
        $this->nameEn = $nameEn;
        $this->authorsIds = $authorsIds;
    }

    public function getNameRu(): string
    {
        return $this->nameRu;
    }

    public function getNameEn(): string
    {
        return $this->nameEn;
    }

    public function getAuthorsIds(): array
    {
        return $this->authorsIds;
    }
}
