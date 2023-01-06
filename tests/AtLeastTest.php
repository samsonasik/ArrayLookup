<?php

declare(strict_types=1);

namespace ArrayLookup\Tests;

use ArrayLookup\AtLeast;
use PHPUnit\Framework\TestCase;

final class AtLeastTest extends TestCase
{
    /**
     * @dataProvider onceDataProvider
     */
    public function testOnce(array $data, callable $callable, bool $expected): void
    {
        $this->assertSame(
            $expected,
            AtLeast::once($data, $callable)
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

    /**
     * @dataProvider twiceDataProvider
     */
    public function testTwice(array $data, callable $callable, bool $expected): void
    {
        $this->assertSame(
            $expected,
            AtLeast::twice($data, $callable)
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

    /**
     * @dataProvider timesDataProvider
     */
    public function testTimes(array $data, callable $callable, bool $expected): void
    {
        $this->assertSame(
            $expected,
            AtLeast::times($data, $callable, 3)
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
}
