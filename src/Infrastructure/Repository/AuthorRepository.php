<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Application\Model\Author\Author;
use App\Application\Model\Author\AuthorRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class AuthorRepository extends ServiceEntityRepository implements AuthorRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Author::class);
    }

    /**
     * @param int $id
     * @return Author|null|Object
     */
    public function findOne(int $id): ?Author
    {
        return $this->find($id);
    }

    /**
     * @param array<int> $ids
     * @return Author[]
     */
    public function findMany(array $ids): array
    {
        return $this->findBy(['id' => $ids]);
    }

    public function add(Author $author): void
    {
        $this->getEntityManager()->persist($author);
        $this->getEntityManager()->flush();
    }

    public function remove(Author $author): void
    {
        $this->getEntityManager()->remove($author);
        $this->getEntityManager()->flush();
    }
}
