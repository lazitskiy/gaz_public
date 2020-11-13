<?php

declare(strict_types=1);

namespace App\DataFixtures\Book;

use App\Application\Model\Author\Author;
use App\Application\Model\Book\Book;
use App\DataFixtures\Author\AuthorFixture;
use App\DataFixtures\BaseFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Generator;

class BookFixture extends BaseFixture implements DependentFixtureInterface
{
    protected function loadData(ObjectManager $manager)
    {
        $this->writeln('Seeding books');

        $getTextValid = function (Generator $faker, $minLength): string {
            do {
                $name = $faker->realText($this->faker->randomElement([16, 32, 48, 62, 96]));
            } while (mb_strlen($name) < $minLength);

            return $name;
        };

        $this->createMany(Book::class, 10000, function () use ($getTextValid) {

            $authors = [];
            for ($i = 0; $i <= array_rand([1, 2, 3, 4, 5]); $i++) {
                $authors[] = $this->getRandomReference(Author::class);
            }

            return [
                $getTextValid($this->fakerRu, 6),
                $getTextValid($this->faker, 6),
                $authors,
            ];
        });
    }

    public function getDependencies()
    {
        return [
            AuthorFixture::class,
        ];
    }
}
