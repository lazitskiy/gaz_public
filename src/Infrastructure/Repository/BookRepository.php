<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Application\Model\Book\Book;
use App\Application\Model\Book\BookRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class BookRepository extends ServiceEntityRepository implements BookRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    /**
     * @param int $id
     * @return Book|null|Object
     */
    public function findOne(int $id): ?Book
    {
        return $this->find($id);
    }

    /**
     * @param array<int> $ids
     * @return Book[]
     */
    public function findMany(array $ids): array
    {
        return $this->findBy(['id' => $ids]);
    }

    public function add(Book $book): void
    {
        $this->getEntityManager()->persist($book);
        $this->getEntityManager()->flush();
    }

    public function remove(Book $book): void
    {
        $this->getEntityManager()->remove($book);
        $this->getEntityManager()->flush();
    }
}
