<?php

declare(strict_types=1);

namespace App\Infrastructure\Service\Assert;

use App\Application\Exception\InvalidInputDataException;
use Webmozart\Assert\Assert as WebmozartAssert;

final class Assert extends WebmozartAssert
{
    /**
     * @param string $message
     *
     * @throws InvalidInputDataException
     */
    protected static function reportInvalidArgument($message): void
    {
        throw new InvalidInputDataException($message);
    }
}
