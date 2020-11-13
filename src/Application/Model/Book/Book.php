<?php

declare(strict_types=1);

namespace App\Application\Model\Book;

use App\Application\Model\Aggregate;
use App\Application\Model\Author\Author;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface;
use Knp\DoctrineBehaviors\Model\Translatable\TranslatableTrait;

/**
 * @ORM\Entity()
 */
class Book extends Aggregate implements TranslatableInterface
{
    use BookGS;
    use TranslatableTrait;

    public const MIN_NAME_LENGTH = 5;
    public const MAX_NAME_LENGTH = 255;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer", options={"unsigned"=true})
     */
    private int $id;

    /**
     * @ORM\ManyToMany(targetEntity="App\Application\Model\Author\Author", mappedBy="books", cascade={"persist", "remove"})
     */
    private PersistentCollection $authors;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private \DateTimeImmutable $createdAt;

    /**
     * @param string $nameRu
     * @param string $nameEn
     * @param Author[] $authors
     */
    public function __construct(string $nameRu, string $nameEn, array $authors)
    {
        $this->translate('ru')->setName($nameRu);
        $this->translate('en')->setName($nameEn);
        $this->mergeNewTranslations();

        $this->setAuthors($authors);
        $this->setCreatedAt(new \DateTimeImmutable());

        $this->raise(new BookCreatedEvent($this));
    }

    public function getName(): string
    {
        return $this->proxyCurrentLocaleTranslation('name');
    }
}
