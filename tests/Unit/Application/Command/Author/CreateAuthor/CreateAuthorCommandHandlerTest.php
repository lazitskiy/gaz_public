<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Command\Author\CreateAuthor;

use App\Application\Command\Author\CreateAuthor\CreateAuthorCommand;
use App\Application\Command\Author\CreateAuthor\CreateAuthorCommandHandler;
use App\Application\Model\Author\Author;
use App\Application\Model\Author\AuthorRepositoryInterface;
use PHPUnit\Framework\TestCase;

class CreateAuthorCommandHandlerTest extends TestCase
{
    public function test_it_creates_author_when_invoked(): void
    {
        $name = 'Author name';

        $repository = $this->createMock(AuthorRepositoryInterface::class);
        $repository
            ->expects(self::once())
            ->method('add')
            ->with(self::callback(fn(Author $author): bool => $author->getName() == $name));

        $command = new CreateAuthorCommand($name);
        $handler = new CreateAuthorCommandHandler($repository);

        try {
            $handler($command);
        } catch (\Error $e) {
            if (strpos($e->getMessage(), 'id must not be accessed before initialization') === false) {
                throw $e;
            }
        }
    }
}
