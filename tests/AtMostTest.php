<?php

declare(strict_types=1);

namespace ArrayLookup\Tests;

use ArrayLookup\AtMost;
use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class AtMostTest extends TestCase
{
    /**
     * @param int[]|string[] $data
     */
    #[DataProvider('onceDataProvider')]
    public function testOnce(array $data, callable $filter, bool $expected): void
    {
        $this->assertSame(
            $expected,
            AtMost::once($data, $filter)
        );
    }

    // phpcs:disable
    /**
     * @return Iterator<mixed>
     */
    public static function onceDataProvider(): Iterator
    {
        yield [
            [1, 2, 3],
            static fn($datum): bool => $datum === 4,
            true,
        ];
        yield [
            [1, 2, 3],
            static fn($datum): bool => $datum === 1,
            true,
        ];
        yield [
            [1, '1', 3],
            static fn($datum): bool => $datum == 1,
            false,
        ];
        yield [
            ['abc', 'def', 'some test'],
            static fn(string $datum, int $key): bool => $datum === 'def' && $key === 1,
            true,
        ];
        yield [
            ['abc', 'def', 'some test'],
            static fn(string $datum, int $key): bool => $key > 0,
            false,
        ];
    }

    // phpcs:enable

    /**
     * @param int[]|string[] $data
     */
    #[DataProvider('twiceDataProvider')]
    public function testTwice(array $data, callable $filter, bool $expected): void
    {
        $this->assertSame(
            $expected,
            AtMost::twice($data, $filter)
        );
    }

    // phpcs:disable
    /**
     * @return Iterator<mixed>
     */
    public static function twiceDataProvider(): Iterator
    {
        yield [
            [1, '1', 3],
            static fn($datum): bool => $datum == 1,
            true,
        ];
        yield [
            [1, '1', 2, 1],
            static fn($datum): bool => $datum == 1,
            false,
        ];
        yield [
            ['abc', 'def', 'some test'],
            static fn(string $datum, int $key): bool => $datum !== 'abc' && $key > 0,
            true,
        ];
        yield [
            ['abc', 'def', 'some test', 'another'],
            static fn(string $datum, int $key): bool => $datum !== 'abc' && $key > 0,
            false,
        ];
    }

    // phpcs:enable

    /**
     * @param int[]|bool[]|null[]|string[] $data
     */
    #[DataProvider('timesDataProvider')]
    public function testTimes(array $data, callable $filter, bool $expected): void
    {
        $this->assertSame(
            $expected,
            AtMost::times($data, $filter, 3)
        );
    }

    /**
     * @return Iterator<mixed>
     */
    public static function timesDataProvider(): Iterator
    {
        yield [
            [0, false, null],
            static fn($datum): bool => ! $datum,
            true,
        ];
        yield [
            [0, false, null, 'x'],
            static fn($datum): bool => ! $datum,
            true,
        ];
        yield [
            [0, false, null, 0],
            static fn($datum): bool => ! $datum,
            false,
        ];
        yield [
            ['abc', 'def', 'some test', 'another test'],
            static fn(string $datum, int $key): bool => $datum !== 'abc' && $key > 0,
            true,
        ];
        yield [
            ['abc', 'def', 'some test', 'another test', 'yet another'],
            static fn(string $datum, int $key): bool => $datum !== 'abc' && $key > 0,
            false,
        ];
    }
}
