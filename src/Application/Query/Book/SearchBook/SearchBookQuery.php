<?php

declare(strict_types=1);

namespace App\Application\Query\Book\SearchBook;

use App\Infrastructure\ValueObject\Pagination\Pagination;

final class SearchBookQuery
{
    private Pagination $pagination;

    private ?string $searchText;

    private string $locale;

    public function __construct(Pagination $pagination, $locale = 'en', ?string $searchText = null)
    {
        $this->pagination = $pagination;
        $this->searchText = $searchText;
        $this->locale = $locale;
    }

    public function getPagination(): Pagination
    {
        return $this->pagination;
    }

    public function getSearchText(): ?string
    {
        return $this->searchText;
    }

    public function getLocale(): string
    {
        return $this->locale;
    }
}
