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
use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use stdClass;

use function current;
use function str_contains;

final class FinderTest extends TestCase
{
    /**
     * @param int[]|string[] $data
     */
    #[DataProvider('firstDataProvider')]
    public function testFirst(iterable $data, callable $filter, mixed $expected): void
    {
        $this->assertSame(
            $expected,
            Finder::first($data, $filter)
        );
    }

    /**
     * @return Iterator<mixed>
     */
    public static function firstDataProvider(): Iterator
    {
        yield [
            [1, 2, 3],
            static fn($datum): bool => $datum === 2,
            2,
        ];
        yield [
            [1, "1", 3],
            static fn($datum): bool => $datum === 1000,
            null,
        ];
        yield [
            ['abc test', 'def', 'some test'],
            static fn(string $datum, int $key): bool => str_contains($datum, 'test') && $key >= 0,
            'abc test',
        ];
        yield [
            ['abc test', 'def', 'some test'],
            static fn(string $datum, int $key): bool => str_contains($datum, 'test') && $key === 1,
            null,
        ];
    }

    /**
     * @param int[]|string[] $data
     */
    #[DataProvider('firstReturnKeyDataProvider')]
    public function testFirstReturnKey(iterable $data, callable $filter, mixed $expected): void
    {
        $this->assertSame(
            $expected,
            Finder::first($data, $filter, true)
        );
    }

    /**
     * @return Iterator<mixed>
     */
    public static function firstReturnKeyDataProvider(): Iterator
    {
        yield [
            [1, 2, 3],
            static fn($datum): bool => $datum === 2,
            1,
        ];
        yield [
            [1, "1", 3],
            static fn($datum): bool => $datum === 1000,
            null,
        ];
        yield [
            ['abc test', 'def', 'some test'],
            static fn(string $datum, int $key): bool => str_contains($datum, 'test') && $key >= 0,
            0,
        ];
        yield [
            ['abc test', 'def', 'some test'],
            static fn(string $datum, int $key): bool => str_contains($datum, 'test') && $key === 1,
            null,
        ];
    }

    /**
     * @param mixed[] $data
     */
    #[DataProvider('lastDataProvider')]
    public function testLast(iterable $data, callable $filter, mixed $expected): void
    {
        $this->assertSame(
            $expected,
            Finder::last($data, $filter)
        );
    }

    /**
     * @return Iterator<mixed>
     */
    public static function lastDataProvider(): Iterator
    {
        $generator = static function (): Generator {
            yield 6;
            yield 7;
            yield 8;
            yield 9;
        };
        yield [
            [6, 7, 8, 9],
            static fn($datum): bool => $datum > 5,
            9,
        ];
        yield [
            [6, 7, 8, 9],
            static fn($datum): bool => $datum < 5,
            null,
        ];
        yield [
            new ArrayIterator([6, 7, 8, 9]),
            static fn($datum): bool => $datum > 5,
            9,
        ];
        yield [
            new ArrayIterator([6, 7, 8, 9]),
            static fn($datum): bool => $datum < 5,
            null,
        ];
        yield [
            new ArrayObject([6, 7, 8, 9]),
            static fn($datum): bool => $datum > 5,
            9,
        ];
        yield [
            new ArrayObject([6, 7, 8, 9]),
            static fn($datum): bool => $datum < 5,
            null,
        ];
        yield [
            $generator(),
            static fn($datum): bool => $datum > 5,
            9,
        ];
        yield [
            $generator(),
            static fn($datum): bool => $datum < 5,
            null,
        ];
        yield [
            ['abc test', 'def', 'some test'],
            static fn(string $datum, int $key): bool => str_contains($datum, 'test') && $key >= 0,
            'some test',
        ];
        yield [
            ['abc test', 'def', 'some test'],
            static fn(string $datum, int $key): bool => str_contains($datum, 'test') && $key === 1,
            null,
        ];
    }

    /**
     * @param mixed[] $data
     */
    #[DataProvider('lastReturnKeyDataProvider')]
    public function testLastReturnKey(iterable $data, callable $filter, mixed $expected): void
    {
        $this->assertSame(
            $expected,
            Finder::last($data, $filter, true)
        );
    }

    /**
     * @return Iterator<mixed>
     */
    public static function lastReturnKeyDataProvider(): Iterator
    {
        $generator = static function (): Generator {
            yield 6;
            yield 7;
            yield 8;
            yield 9;
        };
        yield [
            [6, 7, 8, 9],
            static fn($datum): bool => $datum > 5,
            3,
        ];
        yield [
            [6, 7, 8, 9],
            static fn($datum): bool => $datum < 5,
            null,
        ];
        yield [
            new ArrayIterator([6, 7, 8, 9]),
            static fn($datum): bool => $datum > 5,
            3,
        ];
        yield [
            new ArrayIterator([6, 7, 8, 9]),
            static fn($datum): bool => $datum < 5,
            null,
        ];
        yield [
            new ArrayObject([6, 7, 8, 9]),
            static fn($datum): bool => $datum > 5,
            3,
        ];
        yield [
            new ArrayObject([6, 7, 8, 9]),
            static fn($datum): bool => $datum < 5,
            null,
        ];
        yield [
            $generator(),
            static fn($datum): bool => $datum > 5,
            3,
        ];
        yield [
            $generator(),
            static fn($datum): bool => $datum < 5,
            null,
        ];
        yield [
            ['abc test', 'def', 'some test'],
            static fn(string $datum, int $key): bool => str_contains($datum, 'test') && $key >= 0,
            2,
        ];
        yield [
            ['abc test', 'def', 'some test'],
            static fn(string $datum, int $key): bool => str_contains($datum, 'test') && $key === 1,
            null,
        ];
    }

    /**
     * @param int[] $data
     */
    #[DataProvider('lastReturnKeyResortKeyDataProvider')]
    public function testLastReturnKeyResortKey(iterable $data, callable $filter, mixed $expected): void
    {
        $this->assertSame(
            $expected,
            Finder::last($data, $filter, true, false)
        );
    }

    /**
     * @return Iterator<array<int, (array<int, int>|Closure(mixed $datum):bool|int|null)>>
     */
    public static function lastReturnKeyResortKeyDataProvider(): Iterator
    {
        yield [
            [6, 7, 8, 9],
            static fn($datum): bool => $datum > 5,
            0,
        ];
        yield [
            [6, 7, 8, 9],
            static fn($datum): bool => $datum < 5,
            null,
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

    /**
     * @param array<int|string, int> $expected
     * @param array<int|string, int> $data
     */
    #[DataProvider('rowsDataProvider')]
    public function testRows(iterable $data, callable $filter, array $expected): void
    {
        $this->assertSame(
            $expected,
            Finder::rows($data, $filter)
        );
    }

    /**
     * @return Iterator<mixed>
     */
    public static function rowsDataProvider(): Iterator
    {
        yield [
            [6, 7, 8, 9],
            static fn($datum): bool => $datum > 6,
            [7, 8, 9],
        ];
        yield [
            [6, 7, 8, 9],
            static fn($datum, $key): bool => $datum > 6 && $key > 1,
            [8, 9],
        ];
        yield [
            [6, 7, 8, 9],
            static fn($datum): bool => $datum < 5,
            [],
        ];
        yield [
            [6, 7, 'foo' => 8, 9],
            static fn($datum): bool => $datum > 7,
            ['foo' => 8, 9],
        ];
        // @see https://3v4l.org/0KWZ7Y
        yield [
            [6, 7, '0' => 8, 9],
            static fn($datum): bool => $datum > 7,
            [8, 9],
        ];
    }

    /**
     * @param int[] $expected
     * @param int[] $data
     */
    #[DataProvider('rowsDataProviderPreserveKey')]
    public function testRowsPreserveKey(iterable $data, callable $filter, array $expected): void
    {
        $this->assertSame(
            $expected,
            Finder::rows($data, $filter, true)
        );
    }

    /**
     * @return Iterator<array<int, (array|Closure(mixed $datum):bool)>>
     */
    public static function rowsDataProviderPreserveKey(): Iterator
    {
        yield [
            [6, 7, 8, 9],
            static fn($datum): bool => $datum > 6,
            [
                1 => 7,
                2 => 8,
                3 => 9,
            ],
        ];
        yield [
            [6, 7, 8, 9],
            static fn($datum): bool => $datum < 5,
            [],
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
