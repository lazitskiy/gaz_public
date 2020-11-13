<?php

declare(strict_types=1);

namespace App\Application\Query\Author\GetAuthors;

use App\Infrastructure\ValueObject\Pagination\Pagination;

final class GetAuthorsQuery
{
    private Pagination $pagination;

    private ?string $searchText;

    public function __construct(Pagination $pagination, ?string $searchText = null)
    {
        $this->pagination = $pagination;
        $this->searchText = $searchText;
    }

    public function getPagination(): Pagination
    {
        return $this->pagination;
    }

    public function getSearchText(): ?string
    {
        return $this->searchText;
    }
}
