<?php

declare(strict_types=1);

namespace App\Application\Query\Author\GetAuthors;

use App\Application\Model\Author\Author;
use App\Application\Model\Author\AuthorRepositoryInterface;
use App\Application\Query\Author\DTO\AuthorDTO;
use App\Infrastructure\ValueObject\Pagination\PaginatedData;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\EntityManagerInterface;

final class GetAuthorsQueryHandler
{
    private EntityManagerInterface $em;

    private AuthorRepositoryInterface $authorRepository;

    public function __construct(EntityManagerInterface $em, AuthorRepositoryInterface $authorRepository)
    {
        $this->em = $em;
        $this->authorRepository = $authorRepository;
    }

    public function __invoke(GetAuthorsQuery $query): PaginatedData
    {
        $qb = $this->buildQuery($query);

        $authors = $this->em->getConnection()->executeQuery($qb->getSQL(), $qb->getParameters())->fetchAllAssociative();

        $authorDTOs = [];
        foreach ($authors as $author) {
            $authorDTOs[] = AuthorDTO::fromQueryArray($author);
        }

        $qb = $this->buildQuery($query)
            ->select('COUNT(*)')
            ->setMaxResults(null)
            ->setFirstResult(0);

        $count = (int)$this->em->getConnection()->executeQuery($qb->getSQL(), $qb->getParameters())->fetchOne();

        return new PaginatedData($authorDTOs, $count);
    }

    private function buildQuery(GetAuthorsQuery $query): QueryBuilder
    {
        $authorTable = $this->em->getClassMetadata(Author::class)->getTableName();
        $qb = $this->em->getConnection()->createQueryBuilder()
            ->select('t.*')
            ->from($authorTable, 't')
            ->orderBy('t.created_at')
            ->setFirstResult($query->getPagination()->getOffset())
            ->setMaxResults($query->getPagination()->getLimit());

        if ($query->getSearchText() !== null) {
            $qb
                ->andWhere('t.name LIKE :searchText')
                ->setParameter('searchText', "%{$query->getSearchText()}%");
        }

        return $qb;
    }
}
