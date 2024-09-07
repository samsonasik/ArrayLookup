<?php

declare(strict_types=1);

namespace ArrayLookup;

use Traversable;
use Webmozart\Assert\Assert;

final class Collector
{
    /** @var array<int|string, mixed>|Traversable<int|string, mixed> */
    private iterable $data = [];

    /** @var callable(mixed $datum, int|string|null $key=): bool|null */
    private $when;

    /** @var callable(mixed $datum, int|string|null $key=): mixed */
    private $transform;

    private ?int $limit = null;

    /**
     * @param array<int|string, mixed>|Traversable<int|string, mixed> $data
     */
    public static function setUp(iterable $data): self
    {
        $self       = new self();
        $self->data = $data;

        return $self;
    }

    /**
     * @param callable(mixed $datum, int|string|null $key=): bool $filter
     */
    public function when(callable $filter): self
    {
        $this->when = $filter;
        return $this;
    }

    public function withLimit(int $limit): self
    {
        Assert::positiveInteger($limit);
        $this->limit = $limit;

        return $this;
    }

    /**
     * @param callable(mixed $datum, int|string|null $key=): mixed $transform
     */
    public function withTransform(callable $transform): self
    {
        $this->transform = $transform;
        return $this;
    }

    /**
     * @return mixed[]
     */
    public function getResults(): array
    {
        // ensure when property is set early via ->when() and ->withTransform() method
        Assert::isCallable($this->when);
        Assert::isCallable($this->transform);

        $count         = 0;
        $collectedData = [];

        foreach ($this->data as $key => $datum) {
            $isFound = ($this->when)($datum, $key);

            Assert::boolean($isFound);

            if (! $isFound) {
                continue;
            }

            $collectedData[] = ($this->transform)($datum, $key);

            ++$count;

            if ($this->limit === $count) {
                break;
            }
        }

        return $collectedData;
    }
}
