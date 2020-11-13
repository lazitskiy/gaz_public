<?php

declare(strict_types=1);

namespace App\Application\EventHandler\Author\LogAuthorLiveCycleChanges;

use App\Application\Model\Author\AuthorCreatedEvent;
use Psr\Log\LoggerInterface;

final class AuthorCreatedEventHandler
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function __invoke(AuthorCreatedEvent $event): void
    {
        $this->logger->info(sprintf('Author %s was created', $event->getAuthor()->getId()));
    }
}
