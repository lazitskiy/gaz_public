<?php

declare(strict_types=1);

namespace App\Application\Model\Author;

use App\Application\Model\Aggregate;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;

/**
 * @ORM\Entity()
 */
class Author extends Aggregate
{
    use AuthorGS;

    public const MIN_NAME_LENGTH = 5;
    public const MAX_NAME_LENGTH = 255;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer", options={"unsigned"=true})
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private string $name;

    /**
     * @ORM\ManyToMany(targetEntity="App\Application\Model\Book\Book", inversedBy="authors")
     * @ORM\JoinTable(name="author_book")
     */
    private PersistentCollection $books;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private \DateTimeImmutable $createdAt;

    public function __construct(string $name)
    {
        $this->setName($name);
        $this->setCreatedAt(new \DateTimeImmutable());

        $this->raise(new AuthorCreatedEvent($this));
    }

    // API
    public function changeName(string $name): void
    {
        $this->setName($name);
    }
}
