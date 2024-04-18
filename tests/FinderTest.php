<?php

declare(strict_types=1);

namespace ArrayLookup\Tests;

use ArrayIterator;
use ArrayLookup\Finder;
use ArrayObject;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use Generator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use stdClass;

use function current;
use function str_contains;

final class FinderTest extends TestCase
{
    #[DataProvider('firstDataProvider')]
    public function testFirst(iterable $data, callable $filter, mixed $expected): void
    {
        $this->assertSame(
            $expected,
            Finder::first($data, $filter)
        );
    }

    public static function firstDataProvider(): array
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
            [
                ['abc test', 'def', 'some test'],
                static fn(string $datum, int $key): bool => str_contains($datum, 'test') && $key >= 0,
                'abc test',
            ],
            [
                ['abc test', 'def', 'some test'],
                static fn(string $datum, int $key): bool => str_contains($datum, 'test') && $key === 1,
                null,
            ],
        ];
    }

    #[DataProvider('firstReturnKeyDataProvider')]
    public function testFirstReturnKey(iterable $data, callable $filter, mixed $expected): void
    {
        $this->assertSame(
            $expected,
            Finder::first($data, $filter, true)
        );
    }

    public static function firstReturnKeyDataProvider(): array
    {
        return [
            [
                [1, 2, 3],
                static fn($datum): bool => $datum === 2,
                1,
            ],
            [
                [1, "1", 3],
                static fn($datum): bool => $datum === 1000,
                null,
            ],
            [
                ['abc test', 'def', 'some test'],
                static fn(string $datum, int $key): bool => str_contains($datum, 'test') && $key >= 0,
                0,
            ],
            [
                ['abc test', 'def', 'some test'],
                static fn(string $datum, int $key): bool => str_contains($datum, 'test') && $key === 1,
                null,
            ],
        ];
    }

    #[DataProvider('lastDataProvider')]
    public function testLast(iterable $data, callable $filter, mixed $expected): void
    {
        $this->assertSame(
            $expected,
            Finder::last($data, $filter)
        );
    }

    public static function lastDataProvider(): array
    {
        $generator = static function (): Generator {
            yield 6;
            yield 7;
            yield 8;
            yield 9;
        };

        return [
            [
                [6, 7, 8, 9],
                static fn($datum): bool => $datum > 5,
                9,
            ],
            [
                [6, 7, 8, 9],
                static fn($datum): bool => $datum < 5,
                null,
            ],
            [
                new ArrayIterator([6, 7, 8, 9]),
                static fn($datum): bool => $datum > 5,
                9,
            ],
            [
                new ArrayIterator([6, 7, 8, 9]),
                static fn($datum): bool => $datum < 5,
                null,
            ],
            [
                new ArrayObject([6, 7, 8, 9]),
                static fn($datum): bool => $datum > 5,
                9,
            ],
            [
                new ArrayObject([6, 7, 8, 9]),
                static fn($datum): bool => $datum < 5,
                null,
            ],
            [
                $generator(),
                static fn($datum): bool => $datum > 5,
                9,
            ],
            [
                $generator(),
                static fn($datum): bool => $datum < 5,
                null,
            ],
            [
                ['abc test', 'def', 'some test'],
                static fn(string $datum, int $key): bool => str_contains($datum, 'test') && $key >= 0,
                'some test',
            ],
            [
                ['abc test', 'def', 'some test'],
                static fn(string $datum, int $key): bool => str_contains($datum, 'test') && $key === 1,
                null,
            ],
            [
                [],
                static fn(string $datum, int $key): bool => str_contains($datum, 'test') && $key === 1,
                null,
            ],
        ];
    }

    #[DataProvider('lastReturnKeyDataProvider')]
    public function testLastReturnKey(iterable $data, callable $filter, mixed $expected): void
    {
        $this->assertSame(
            $expected,
            Finder::last($data, $filter, true)
        );
    }

    public static function lastReturnKeyDataProvider(): array
    {
        $generator = static function (): Generator {
            yield 6;
            yield 7;
            yield 8;
            yield 9;
        };

        return [
            [
                [6, 7, 8, 9],
                static fn($datum): bool => $datum > 5,
                3,
            ],
            [
                [6, 7, 8, 9],
                static fn($datum): bool => $datum < 5,
                null,
            ],
            [
                new ArrayIterator([6, 7, 8, 9]),
                static fn($datum): bool => $datum > 5,
                3,
            ],
            [
                new ArrayIterator([6, 7, 8, 9]),
                static fn($datum): bool => $datum < 5,
                null,
            ],
            [
                new ArrayObject([6, 7, 8, 9]),
                static fn($datum): bool => $datum > 5,
                3,
            ],
            [
                new ArrayObject([6, 7, 8, 9]),
                static fn($datum): bool => $datum < 5,
                null,
            ],
            [
                $generator(),
                static fn($datum): bool => $datum > 5,
                3,
            ],
            [
                $generator(),
                static fn($datum): bool => $datum < 5,
                null,
            ],
            [
                ['abc test', 'def', 'some test'],
                static fn(string $datum, int $key): bool => str_contains($datum, 'test') && $key >= 0,
                2,
            ],
            [
                ['abc test', 'def', 'some test'],
                static fn(string $datum, int $key): bool => str_contains($datum, 'test') && $key === 1,
                null,
            ],
        ];
    }

    #[DataProvider('lastReturnKeyResortKeyDataProvider')]
    public function testLastReturnKeyResortKey(iterable $data, callable $filter, mixed $expected): void
    {
        $this->assertSame(
            $expected,
            Finder::last($data, $filter, true, false)
        );
    }

    public static function lastReturnKeyResortKeyDataProvider(): array
    {
        return [
            [
                [6, 7, 8, 9],
                static fn($datum): bool => $datum > 5,
                0,
            ],
            [
                [6, 7, 8, 9],
                static fn($datum): bool => $datum < 5,
                null,
            ],
        ];
    }

    public function testLastKeepCurrentOriginalData(): void
    {
        $data = [new DateTime('now'), new DateTimeImmutable('now'), new stdClass()];

        // get last
        $last = Finder::last($data, static fn(object $datum): bool => $datum instanceof DateTimeInterface);
        $this->assertInstanceOf(DateTimeImmutable::class, $last);

        // keep first record not changed from data
        $this->assertSame(DateTime::class, current($data)::class);
    }

    #[DataProvider('rowsDataProvider')]
    public function testRows(iterable $data, callable $filter, array $expected): void
    {
        $this->assertSame(
            $expected,
            Finder::rows($data, $filter)
        );
    }

    public static function rowsDataProvider(): array
    {
        return [
            [
                [6, 7, 8, 9],
                static fn($datum): bool => $datum > 6,
                [7, 8, 9],
            ],
            [
                [6, 7, 8, 9],
                static fn($datum, $key): bool => $datum > 6 && $key > 1,
                [8, 9],
            ],
            [
                [6, 7, 8, 9],
                static fn($datum): bool => $datum < 5,
                [],
            ],
            [
                [6, 7, 'foo' => 8, 9],
                static fn($datum): bool => $datum > 7,
                ['foo' => 8, 9],
            ],
            // @see https://3v4l.org/0KWZ7Y
            [
                [6, 7, '0' => 8, 9],
                static fn($datum): bool => $datum > 7,
                [8, 9],
            ],
        ];
    }

    #[DataProvider('rowsDataProviderPreserveKey')]
    public function testRowsPreserveKey(iterable $data, callable $filter, array $expected): void
    {
        $this->assertSame(
            $expected,
            Finder::rows($data, $filter, true)
        );
    }

    public static function rowsDataProviderPreserveKey(): array
    {
        return [
            [
                [6, 7, 8, 9],
                static fn($datum): bool => $datum > 6,
                [
                    1 => 7,
                    2 => 8,
                    3 => 9,
                ],
            ],
            [
                [6, 7, 8, 9],
                static fn($datum): bool => $datum < 5,
                [],
            ],
        ];
    }

    public function testRowsWithLimit(): void
    {
        $data   = [1, 2];
        $filter = static fn ($datum): bool => $datum >= 0;

        $this->assertSame(
            [
                1,
            ],
            Finder::rows($data, $filter, limit: 1)
        );
    }
}
