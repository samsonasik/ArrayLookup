<?php

declare(strict_types=1);

namespace ArrayLookup\Tests;

use ArrayIterator;
use ArrayLookup\Finder;
use ArrayObject;
use Generator;
use PHPUnit\Framework\TestCase;

use function str_contains;

final class FinderTest extends TestCase
{
    /**
     * @dataProvider firstDataProvider
     */
    public function testFirst(iterable $data, callable $filter, mixed $expected): void
    {
        $this->assertSame(
            $expected,
            Finder::first($data, $filter)
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

    /**
     * @dataProvider firstReturnKeyDataProvider
     */
    public function testFirstReturnKey(iterable $data, callable $filter, mixed $expected): void
    {
        $this->assertSame(
            $expected,
            Finder::first($data, $filter, true)
        );
    }

    public function firstReturnKeyDataProvider(): array
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

    /**
     * @dataProvider lastDataProvider
     */
    public function testLast(iterable $data, callable $filter, mixed $expected): void
    {
        $this->assertSame(
            $expected,
            Finder::last($data, $filter)
        );
    }

    public function lastDataProvider(): array
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
        ];
    }

    /**
     * @dataProvider lastReturnKeyDataProvider
     */
    public function testLastReturnKey(iterable $data, callable $filter, mixed $expected): void
    {
        $this->assertSame(
            $expected,
            Finder::last($data, $filter, true)
        );
    }

    public function lastReturnKeyDataProvider(): array
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

    /**
     * @dataProvider lastReturnKeyResortKeyDataProvider
     */
    public function testLastReturnKeyResortKey(iterable $data, callable $filter, mixed $expected): void
    {
        $this->assertSame(
            $expected,
            Finder::last($data, $filter, true, false)
        );
    }

    public function lastReturnKeyResortKeyDataProvider(): array
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
}
