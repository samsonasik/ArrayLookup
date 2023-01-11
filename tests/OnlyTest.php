<?php

declare(strict_types=1);

namespace ArrayLookup\Tests;

use ArrayLookup\Only;
use PHPUnit\Framework\TestCase;
use stdClass;

use function is_int;
use function str_contains;

final class OnlyTest extends TestCase
{
    /**
     * @dataProvider onceDataProvider
     */
    public function testOnce(array $data, callable $filter, bool $expected): void
    {
        $this->assertSame(
            $expected,
            Only::once($data, $filter)
        );
    }

    // phpcs:disable
    public function onceDataProvider(): array
    {
        return [
            [
                [1, 2, 3],
                static fn($datum): bool => $datum === 1,
                true,
            ],
            [
                [1, "1", 3],
                static fn($datum): bool => $datum == 1,
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

        $this->assertTrue(Only::once($data, $filter, true));
        $this->assertFalse(Only::once($data, $filter, false));
    }

    // phpcs:enable

    /**
     * @dataProvider twiceDataProvider
     */
    public function testTwice(array $data, callable $filter, bool $expected): void
    {
        $this->assertSame(
            $expected,
            Only::twice($data, $filter)
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
                [true, 1, new stdClass()],
                static fn($datum): bool => (bool) $datum,
                false,
            ],
        ];
    }

    public function testTwiceIncludekey(): void
    {
        $filter = static fn(mixed $datum, ?int $key): bool => str_contains((string) $datum, 'test') && is_int($key);
        $data   = [
            0 => 'abc',
            1 => 'def test',
            2 => 'some test',
        ];

        $this->assertTrue(Only::twice($data, $filter, true));
        $this->assertFalse(Only::twice($data, $filter, false));
    }

    // phpcs:enable

    /**
     * @dataProvider timesDataProvider
     */
    public function testTimes(array $data, callable $filter, bool $expected): void
    {
        $this->assertSame(
            $expected,
            Only::times($data, $filter, 3)
        );
    }

    public function timesDataProvider(): array
    {
        return [
            [
                [6, 7, 8, 9],
                static fn($datum): bool => $datum > 6,
                true,
            ],
            [
                [6, 7, 8, 9],
                static fn($datum): bool => $datum > 7,
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

        $this->assertTrue(Only::times($data, $filter, 3, true));
        $this->assertFalse(Only::times($data, $filter, 3, false));
    }
}
