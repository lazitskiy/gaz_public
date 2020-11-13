<?php

declare(strict_types=1);

namespace App\DataFixtures\Author;

use App\Application\Model\Author\Author;
use App\DataFixtures\BaseFixture;
use Doctrine\Persistence\ObjectManager;

class AuthorFixture extends BaseFixture
{
    protected function loadData(ObjectManager $manager)
    {
        $this->writeln('Seeding authors');

        $this->createMany(Author::class, 10000, function () {
            return [
                $this->faker->name(),
            ];
        });
    }
}
