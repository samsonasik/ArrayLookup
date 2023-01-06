<?php

declare(strict_types=1);

namespace ArrayLookup\Tests;

use ArrayLookup\AtLeast;
use PHPUnit\Framework\TestCase;

final class AtLeastTest extends TestCase
{
    /**
     * @dataProvider atLeastOnceDataProvider
     */
    public function testAtLeastOnce(array $data, callable $callable, bool $expected): void
    {
        $this->assertSame(
            $expected,
            AtLeast::atLeastOnce($data, $callable)
        );
    }

    public function atLeastOnceDataProvider(): array
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
     * @dataProvider atLeastOnceDataProvider
     */
    public function testAtLeastTwice(array $data, callable $callable, bool $expected): void
    {
        $this->assertSame(
            $expected,
            AtLeast::atLeastTwice($data, $callable)
        );
    }

    public function atLeastTwiceDataProvider(): array
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

    /**
     * @dataProvider atLeastTimesDataProvider
     */
    public function testAtLeastTime(array $data, callable $callable, bool $expected): void
    {
        $this->assertSame(
            $expected,
            AtLeast::atLeastTimes($data, $callable, 3)
        );
    }

    public function atLeastTimesDataProvider(): array
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
