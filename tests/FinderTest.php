<?php

declare(strict_types=1);

namespace ArrayLookup\Tests;

use ArrayLookup\Finder;
use DateTime;
use PHPUnit\Framework\TestCase;

use function sleep;

final class FinderTest extends TestCase
{
    /**
     * @dataProvider firstDataProvider
     */
    public function testFirst(array $data, callable $callable, mixed $expected): void
    {
        $this->assertSame(
            $expected,
            Finder::first($data, $callable)
        );
    }

    public function firstDataProvider(): array
    {
        return [
            [
                [1, 2, 3],
                static fn($datum): bool => $datum === 2,
                2,
            ],
            [
                [1, "1", 3],
                static fn($datum): bool => $datum === 1000,
                null,
            ],
        ];
    }

    public function testLast(): void
    {
        $dateTime1 = new DateTime('now');

        sleep(1);

        $dateTime2 = new DateTime('now');

        $data = [$dateTime1, $dateTime2];
        $this->assertSame($dateTime2, Finder::last($data, static fn ($datum): bool => $datum instanceof DateTime));
    }
}
