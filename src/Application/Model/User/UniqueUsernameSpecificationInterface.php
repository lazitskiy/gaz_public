<?php

declare(strict_types=1);

namespace App\Application\Model\User;

interface UniqueUsernameSpecificationInterface
{
    public function isSatisfiedBy(string $userName): bool;
}
