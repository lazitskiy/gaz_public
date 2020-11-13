<?php

declare(strict_types=1);

namespace App\Application\Command\Author;

abstract class AuthorCommand
{
    protected string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
