<?php

declare(strict_types=1);

namespace App\Infrastructure\Http;

use App\Infrastructure\Service\Assert\Assert;
use Symfony\Component\HttpFoundation\Request;

final class ParamFetcher
{
    private const TYPE_STRING = 'string';
    private const TYPE_INT = 'int';
    private const TYPE_ARRAY = 'array';

    private const SCALAR_TYPES = [self::TYPE_STRING, self::TYPE_INT];

    /**
     * @var array<string, mixed>
     */
    private array $data;

    private bool $testScalarType;

    /**
     * @param array<string, mixed> $data
     * @param bool $testScalarType
     */
    public function __construct(array $data, bool $testScalarType = true)
    {
        $this->data = $data;
        $this->testScalarType = $testScalarType;
    }

    public static function fromRequestAttributes(Request $request): self
    {
        return new self($request->attributes->all(), false);
    }

    public static function fromRequestBody(Request $request): self
    {
        return new self($request->request->all());
    }

    public static function fromRequestQuery(Request $request): self
    {
        return new self($request->query->all(), false);
    }

    public function getRequiredString(string $key): string
    {
        $this->assertRequired($key);
        $this->assertType($key, self::TYPE_STRING);

        return (string)$this->data[$key];
    }

    public function getNullableString(string $key): ?string
    {
        if (!isset($this->data[$key])) {
            return null;
        }
        $this->assertType($key, self::TYPE_STRING);

        return (string)$this->data[$key];
    }

    public function getRequiredInt(string $key): int
    {
        $this->assertRequired($key);
        $this->assertType($key, self::TYPE_INT);

        return (int)$this->data[$key];
    }

    public function getNullableInt(string $key): ?int
    {
        if (!isset($this->data[$key])) {
            return null;
        }
        $this->assertType($key, self::TYPE_INT);

        return (int)$this->data[$key];
    }

    public function getRequiredArray(string $key): array
    {
        $this->assertRequired($key);
        $this->assertType($key, self::TYPE_ARRAY);

        return (array)$this->data[$key];
    }

    private function assertRequired(string $key): void
    {
        Assert::keyExists($this->data, $key, sprintf('"%s" not found', $key));
        Assert::notNull($this->data[$key], sprintf('"%s" should be not null', $key));
    }

    private function assertType(string $key, string $type): void
    {
        if (!$this->testScalarType && \in_array($type, self::SCALAR_TYPES, true)) {
            return;
        }

        switch ($type) {
            case self::TYPE_STRING:
                Assert::string($this->data[$key], sprintf('"%s" should be a string. Got %%s', $key));
                break;

            case self::TYPE_INT:
                Assert::string($this->data[$key], sprintf('"%s" should be an integer. Got %%s', $key));
                break;

            case self::TYPE_ARRAY:
                Assert::isArray($this->data[$key], sprintf('"%s" should be an array. Got %%s', $key));
                break;
        }
    }
}
