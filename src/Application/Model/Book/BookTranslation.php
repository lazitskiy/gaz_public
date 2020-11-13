<?php

declare(strict_types=1);

namespace App\Application\Model\Book;

use App\Application\Model\Aggregate;
use App\Infrastructure\Service\Assert\Assert;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TranslationInterface;
use Knp\DoctrineBehaviors\Model\Translatable\TranslationTrait;

/**
 * @ORM\Entity()
 */
class BookTranslation extends Aggregate implements TranslationInterface
{
    use BookGS;
    use TranslationTrait;

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

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        Assert::minLength($name, Book::MIN_NAME_LENGTH, 'Name should contain at least %2$s characters. Got: %s');
        Assert::maxLength($name, Book::MAX_NAME_LENGTH, 'Name should contain at most %2$s characters. Got: %s');
        $this->name = $name;
    }
}
