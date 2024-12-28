<?php

declare(strict_types=1);

namespace ArrayLookup;

use ArrayLookup\Assert\Filter;
use Traversable;
use Webmozart\Assert\Assert;

use function is_callable;

final class Collector
{
    /** @var array<int|string, mixed>|Traversable<int|string, mixed> */
    private iterable $data = [];

    /** @var null|callable(mixed $datum, int|string|null $key): bool */
    private $when;

    /** @var null|callable(mixed $datum, int|string|null $key): mixed */
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
     * @param callable(mixed $datum, int|string|null $key): bool $filter
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
     * @param callable(mixed $datum, int|string|null $key): mixed $transform
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
        // ensure transform property is set early ->withTransform() method
        Assert::isCallable($this->transform);

        $count         = 0;
        $collectedData = [];

        if (is_callable($this->when)) {
            // filter must be a callable with bool return type
            Filter::boolean($this->when);
        }

        foreach ($this->data as $key => $datum) {
            if ($this->when !== null) {
                $isFound = ($this->when)($datum, $key);

                if (! $isFound) {
                    continue;
                }
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
