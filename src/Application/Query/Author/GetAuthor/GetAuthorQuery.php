<?php

declare(strict_types=1);

namespace App\Application\Query\Author\GetAuthor;

final class GetAuthorQuery
{
    private int $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }
}
