<?php

declare(strict_types=1);

namespace App\Application\Command\User\CreateUser;

use App\Application\Model\User\UniqueUsernameSpecificationInterface;
use App\Application\Model\User\User;
use App\Application\Model\User\UserRepositoryInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

final class CreateUserCommandHandler
{
    private EncoderFactoryInterface $encoderFactory;

    private UserRepositoryInterface $userRepository;

    private UniqueUsernameSpecificationInterface $uniqueUsernameSpecification;

    public function __construct(
        EncoderFactoryInterface $encoderFactory,
        UserRepositoryInterface $userRepository,
        UniqueUsernameSpecificationInterface $uniqueUsernameSpecification
    ) {
        $this->encoderFactory = $encoderFactory;
        $this->userRepository = $userRepository;
        $this->uniqueUsernameSpecification = $uniqueUsernameSpecification;
    }

    public function __invoke(CreateUserCommand $command): void
    {
        $encoder = $this->encoderFactory->getEncoder(User::class);
        $user = new User(
            $command->getUsername(),
            $encoder->encodePassword($command->getPassword(), null),
            $this->uniqueUsernameSpecification
        );
        $this->userRepository->add($user);
    }
}
