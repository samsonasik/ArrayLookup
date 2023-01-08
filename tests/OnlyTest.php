<?php

declare(strict_types=1);

namespace ArrayLookup\Tests;

use ArrayLookup\Only;
use PHPUnit\Framework\TestCase;
use stdClass;

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
}
