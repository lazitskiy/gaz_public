<?php

declare(strict_types=1);

namespace App\Application\Model\User;

interface UserFetcherInterface
{
    public function fetchRequiredUser(): User;

    public function fetchNullableUser(): ?User;
}
