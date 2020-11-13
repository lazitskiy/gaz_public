<?php

declare(strict_types=1);

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\ConsoleOutputInterface;

abstract class BaseFixture extends Fixture
{
    protected ObjectManager $manager;
    protected ConsoleOutputInterface $output;
    protected Generator $faker;
    protected Generator $fakerRu;

    private array $referencesIndex = [];

    abstract protected function loadData(ObjectManager $manager);

    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;
        $this->output = new ConsoleOutput();
        $this->faker = Factory::create();
        $this->fakerRu = Factory::create('ru_RU');

        $this->loadData($manager);
    }

    protected function createMany(string $className, int $count, callable $constructArgs, callable $factory = null)
    {
        $progressBar = new ProgressBar($this->output, $count);

        for ($i = 0; $i < $count; ++$i) {

            $progressBar->advance();

            $entity = new $className(...$constructArgs());
            if (false === is_null($factory)) {
                $factory($entity, $i);
            }
            $this->manager->persist($entity);

            $this->addReference($className . '_' . $i, $entity);
        }
        $progressBar->finish();
        $this->manager->flush();
    }

    protected function getRandomReference(string $className)
    {
        if (!isset($this->referencesIndex[$className])) {
            $this->referencesIndex[$className] = [];
            foreach ($this->referenceRepository->getReferences() as $key => $ref) {
                if (strpos($key, $className . '_') === 0) {
                    $this->referencesIndex[$className][] = $key;
                }
            }
        }
        if (empty($this->referencesIndex[$className])) {
            throw new \Exception(sprintf('Cannot find any references for class "%s"', $className));
        }
        $randomReferenceKey = $this->faker->randomElement($this->referencesIndex[$className]);

        return $this->getReference($randomReferenceKey);
    }

    protected function writeln(string $messages)
    {
        echo $messages . PHP_EOL;
    }

    protected function writeReplace(string $messages)
    {
        echo "$messages\033[2K\r";
    }
}
