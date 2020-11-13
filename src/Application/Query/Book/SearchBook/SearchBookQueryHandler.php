<?php

declare(strict_types=1);

namespace App\Application\Query\Book\SearchBook;

use App\Application\Model\Book\Book;
use App\Application\Query\Book\DTO\BookDTO;
use App\Infrastructure\ValueObject\Pagination\PaginatedData;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;

final class SearchBookQueryHandler
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function __invoke(SearchBookQuery $query): PaginatedData
    {
        $qb = $this->buildQuery($query);
        $books = $qb->getQuery()->getResult();

        $bookDTOs = [];
        foreach ($books as $book) {
            $bookDTOs[] = BookDTO::fromEntity($book);
        }

        $qb = $this->buildQuery($query)
            ->select('COUNT(t)')
            ->setMaxResults(null)
            ->setFirstResult(0);

        $count = (int)$qb->getQuery()->getSingleScalarResult();

        return new PaginatedData($bookDTOs, $count);
    }

    private function buildQuery(SearchBookQuery $query): QueryBuilder
    {
        $qb = $this->em->createQueryBuilder()
            ->select('t')
            ->from(Book::class, 't')
            ->leftJoin('t.translations', 'bt', Join::WITH, "bt.locale = '{$query->getLocale()}'")
            ->orderBy('t.createdAt')
            ->setFirstResult($query->getPagination()->getOffset())
            ->setMaxResults($query->getPagination()->getLimit());

        if ($query->getSearchText() !== null) {
            $qb
                ->andWhere('bt.name LIKE :searchText')
                ->setParameter('searchText', "%{$query->getSearchText()}%");
        }

        return $qb;
    }
}
