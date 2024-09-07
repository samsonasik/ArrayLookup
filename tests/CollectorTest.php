<?php

declare(strict_types=1);

namespace ArrayLookup\Tests;

use ArrayLookup\Collector;
use PHPUnit\Framework\TestCase;
use stdClass;

use function is_string;
use function trim;

final class CollectorTest extends TestCase
{
    public function testWithoutWhen(): void
    {
        $data = [
            ' a ',
            ' b ',
            ' c ',
        ];

        $results = Collector::setUp($data)
            ->withTransform(fn (string $datum): string => trim($datum))
            ->getResults();

        $this->assertSame(['a', 'b', 'c'], $results);
    }

    public function testWithoutLimit(): void
    {
        $data = [
            ' a ',
            ' b ',
            ' c ',
            new stdClass(),
        ];

        $results = Collector::setUp($data)
            ->when(fn (mixed $datum): bool => is_string($datum))
            ->withTransform(fn (string $datum): string => trim($datum))
            ->getResults();

        $this->assertSame(['a', 'b', 'c'], $results);
    }

    public function testWithLimit(): void
    {
        $data = [
            ' a ',
            ' b ',
            ' c ',
            new stdClass(),
        ];

        $results = Collector::setUp($data)
            ->when(fn (mixed $datum): bool => is_string($datum))
            ->withTransform(fn (string $datum): string => trim($datum))
            ->withLimit(1)
            ->getResults();

        $this->assertSame(['a'], $results);
    }
}
