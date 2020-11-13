<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\Command\Author\CreateAuthor;

use App\Application\Command\Author\CreateAuthor\CreateAuthorCommand;
use App\Application\Command\Author\CreateAuthor\CreateAuthorCommandHandler;
use App\Application\Command\User\CreateUser\CreateUserCommand;
use App\Application\Command\User\CreateUser\CreateUserCommandHandler;
use App\Application\Model\Author\Author;
use App\Application\Model\Author\AuthorRepositoryInterface;
use App\Application\Model\User\UniqueUsernameSpecificationInterface;
use App\Application\Model\User\User;
use App\Application\Model\User\UserRepositoryInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

class CreateUserCommandHandlerTest extends TestCase
{
    public function test_it_creates_user_when_invoked(): void
    {
        $userName = 'admin';
        $password = 'hash';

        $repository = $this->createMock(UserRepositoryInterface::class);
        $repository
            ->expects(self::once())
            ->method('add')
            ->with(self::callback(fn(User $user): bool => $user->getUsername() == $userName));

        $command = new CreateUserCommand($userName, $password);
        $handler = new CreateUserCommandHandler($this->getEncoderFactory(), $repository, $this->getUniqueUsernameSpecification());

        try {
            $handler($command);
        } catch (\Error $e) {
            if (strpos($e->getMessage(), 'id must not be accessed before initialization') === false) {
                throw $e;
            }
        }
    }

    private function getUniqueUsernameSpecification(): UniqueUsernameSpecificationInterface
    {
        $specification = $this->createMock(UniqueUsernameSpecificationInterface::class);
        $specification->method('isSatisfiedBy')->willReturn(true);

        return $specification;
    }

    private function getEncoderFactory(): EncoderFactoryInterface
    {
        $passwordEncoder = $this->createMock(PasswordEncoderInterface::class);
        $passwordEncoder->method('encodePassword')->willReturn('some_hash');

        $encoder = $this->createMock(EncoderFactoryInterface::class);
        $encoder->method('getEncoder')->willReturn($passwordEncoder);

        return $encoder;
    }
}
