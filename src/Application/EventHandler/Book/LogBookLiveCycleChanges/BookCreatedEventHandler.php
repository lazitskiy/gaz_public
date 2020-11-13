<?php

declare(strict_types=1);

namespace App\Application\EventHandler\Book\LogBookLiveCycleChanges;

use App\Application\Model\Book\BookCreatedEvent;
use Psr\Log\LoggerInterface;

final class BookCreatedEventHandler
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function __invoke(BookCreatedEvent $event): void
    {
        $this->logger->info(sprintf('Book %s was created', $event->getBook()->getId()));
    }
}
