<?php

declare(strict_types=1);

namespace App\Application\Model\Book;

use App\Application\Model\Author\Author;
use Doctrine\ORM\PersistentCollection;

trait BookGS
{
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return PersistentCollection|Author[]
     */
    public function getAuthors()
    {
        return $this->authors;
    }

    /**
     * @param PersistentCollection|Author[] $authors
     */
    public function setAuthors($authors): void
    {
        foreach ($authors as $author) {
            if(!$author->getBooks()->contains($this)){
                $author->addBook($this);
            }
        }
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    private function setCreatedAt(\DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}
