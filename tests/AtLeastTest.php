<?php

declare(strict_types=1);

namespace ArrayLookup\Tests;

use ArrayLookup\AtLeast;
use PHPUnit\Framework\TestCase;

use function is_int;
use function str_contains;

final class AtLeastTest extends TestCase
{
    /**
     * @dataProvider onceDataProvider
     */
    public function testOnce(array $data, callable $filter, bool $expected): void
    {
        $this->assertSame(
            $expected,
            AtLeast::once($data, $filter)
        );
    }

    public function onceDataProvider(): array
    {
        return [
            [
                [1, 2, 3],
                static fn($datum): bool => $datum === 1,
                true,
            ],
            [
                [1, 2, 3],
                static fn($datum): bool => $datum === 4,
                false,
            ],
        ];
    }

    public function testOnceIncludekey(): void
    {
        $filter = static fn(mixed $datum, ?int $key): bool => str_contains((string) $datum, 'test') && is_int($key);
        $data   = [
            0 => 'abc',
            1 => 'def',
            2 => 'some test',
        ];

        $this->assertTrue(AtLeast::once($data, $filter, true));
        $this->assertFalse(AtLeast::once($data, $filter, false));
    }

    /**
     * @dataProvider twiceDataProvider
     */
    public function testTwice(array $data, callable $filter, bool $expected): void
    {
        $this->assertSame(
            $expected,
            AtLeast::twice($data, $filter)
        );
    }

    // phpcs:disable
    public function twiceDataProvider(): array
    {
        return [
            [
                [1, "1", 2],
                static fn($datum): bool => $datum == 1,
                true,
            ],
            [
                [1, "1", 3],
                static fn($datum): bool => $datum === 1,
                false,
            ],
        ];
    }

    // phpcs:enable

    public function testTwiceIncludekey(): void
    {
        $filter = static fn(mixed $datum, ?int $key): bool => str_contains((string) $datum, 'test') && is_int($key);
        $data   = [
            0 => 'abc',
            1 => 'def test',
            2 => 'some test',
        ];

        $this->assertTrue(AtLeast::twice($data, $filter, true));
        $this->assertFalse(AtLeast::twice($data, $filter, false));
    }

    /**
     * @dataProvider timesDataProvider
     */
    public function testTimes(array $data, callable $filter, bool $expected): void
    {
        $this->assertSame(
            $expected,
            AtLeast::times($data, $filter, 3)
        );
    }

    public function timesDataProvider(): array
    {
        return [
            [
                [0, false, null],
                static fn($datum): bool => ! $datum,
                true,
            ],
            [
                [1, false, null],
                static fn($datum): bool => ! $datum,
                false,
            ],
        ];
    }

    public function testTimesIncludekey(): void
    {
        $filter = static fn(mixed $datum, ?int $key): bool => str_contains((string) $datum, 'test') && is_int($key);
        $data   = [
            0 => 'abc test',
            1 => 'def test',
            2 => 'some test',
        ];

        $this->assertTrue(AtLeast::times($data, $filter, 3, true));
        $this->assertFalse(AtLeast::times($data, $filter, 3, false));
    }
}
