<?php

declare(strict_types=1);

namespace ArrayLookup\Tests;

use ArrayLookup\Interval;
use InvalidArgumentException;
use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class IntervalTest extends TestCase
{
    /**
     * @param int[]|string[] $data
     */
    #[DataProvider('inclusiveDataProvider')]
    public function testIsInclusiveOf(
        array $data,
        callable $filter,
        int $min,
        int $max,
        bool $expected
    ): void {
        $this->assertSame(
            $expected,
            Interval::isInclusiveOf($data, $filter, $min, $max)
        );
    }

    /**
     * @return Iterator<mixed>
     */
    public static function inclusiveDataProvider(): Iterator
    {
        yield 'min boundary' => [
            [1, 2, 3],
            static fn($datum): bool => $datum > 1,
            2,
            5,
            true,
        ];
        yield 'inside range' => [
            [1, 2, 3, 4, 5],
            static fn($datum): bool => $datum > 2,
            2,
            5,
            true,
        ];
        yield 'max boundary' => [
            [1, 2, 3, 4, 5],
            static fn($datum): bool => $datum >= 1,
            2,
            5,
            true,
        ];
        yield 'below min' => [
            [1, 2, 3],
            static fn($datum): bool => $datum === 3,
            2,
            5,
            false,
        ];
        yield 'above max' => [
            [1, 2, 3, 4, 5, 6],
            static fn($datum): bool => $datum >= 1,
            2,
            5,
            false,
        ];
        yield 'with key in filter' => [
            ['a', 'b', 'c', 'd', 'e'],
            static fn(string $datum, int $key): bool => $datum !== 'a' && $key > 0,
            2,
            5,
            true,
        ];
    }

    /**
     * @param int[]|string[] $data
     */
    #[DataProvider('exclusiveDataProvider')]
    public function testIsExclusiveOf(
        array $data,
        callable $filter,
        int $min,
        int $max,
        bool $expected
    ): void {
        $this->assertSame(
            $expected,
            Interval::isExclusiveOf($data, $filter, $min, $max)
        );
    }

    /**
     * @return Iterator<mixed>
     */
    public static function exclusiveDataProvider(): Iterator
    {
        yield 'inside range' => [
            [1, 2, 3, 4, 5],
            static fn($datum): bool => $datum > 2,
            2,
            5,
            true,
        ];
        yield 'above min only' => [
            [1, 2, 3],
            static fn($datum): bool => $datum > 1,
            2,
            5,
            false,
        ];
        yield 'at min boundary' => [
            [1, 2, 3],
            static fn($datum): bool => $datum > 2,
            2,
            5,
            false,
        ];
        yield 'at max boundary' => [
            [1, 2, 3, 4, 5],
            static fn($datum): bool => $datum >= 1,
            2,
            5,
            false,
        ];
        yield 'above max' => [
            [1, 2, 3, 4, 5, 6],
            static fn($datum): bool => $datum >= 1,
            2,
            5,
            false,
        ];
    }

    /**
     * @param int[] $data
     */
    public function testNoSpaceIntervalIsExclusiveOf(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The difference between min and max must be greater than 1 for an exclusive interval.');

        $data = [1, 2, 3];
        $filter = static fn($datum): bool => $datum > 1;
        $min = 2;
        $max = 3;

        Interval::isExclusiveOf($data, $filter, $min, $max);
    }
}
