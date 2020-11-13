<?php

declare(strict_types=1);

namespace App\Infrastructure\Specification\User;

use App\Application\Model\User\UniqueUsernameSpecificationInterface;
use App\Application\Model\User\User;
use Doctrine\ORM\EntityManagerInterface;

final class UniqueUsernameSpecification implements UniqueUsernameSpecificationInterface
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function isSatisfiedBy(string $username): bool
    {
        return $this->em->createQueryBuilder()
            ->select('u')
            ->from(User::class, 'u')
            ->where('u.username = :username')
            ->setParameters(['username' => $username])
            ->getQuery()->getOneOrNullResult() === null;
    }
}
