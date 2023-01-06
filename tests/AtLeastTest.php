<?php

namespace ArrayLookup\Tests;

use ArrayLookup;
use PHPUnit\Framework\TestCase;

class AtLeastTest extends TestCase
{
    /**
     * @dataProvider atLeastOnceDataProvider
     */
    public function testAtLeastOnce($data, $callable, $expected): void
    {
        $this->assertSame(
            $expected,
            ArrayLookup\AtLeast::atLeastOnce($data, $callable)
        );
    }

    public function atLeastOnceDataProvider()
    {
        return [
            [
                [1, 2, 3],
                fn ($datum) => $datum === 1,
                true
            ],
            [
                [1, 2, 3],
                fn ($datum) => $datum === 4,
                false
            ]
        ];
    }

    /**
     * @dataProvider atLeastOnceDataProvider
     */
    public function testAtLeastTwice($data, $callable, $expected): void
    {
        $this->assertSame(
            $expected,
            ArrayLookup\AtLeast::atLeastTwice($data, $callable)
        );
    }

    public function atLeastTwiceDataProvider()
    {
        return [
            [
                [1, "1", 2],
                fn ($datum) => $datum == 1,
                true
            ],
            [
                [1, "1", 3],
                fn ($datum) => $datum === 1,
                false
            ]
        ];
    }

    /**
     * @dataProvider atLeastTimesDataProvider
     */
    public function testAtLeastTime($data, $callable, $expected): void
    {
        $this->assertSame(
            $expected,
            ArrayLookup\AtLeast::atLeastTimes($data, $callable, 3)
        );
    }

    public function atLeastTimesDataProvider()
    {
        return [
            [
                [0, false, null],
                fn ($datum) => ! $datum,
                true
            ],
            [
                [1, false, null],
                fn ($datum) => ! $datum,
                false
            ]
        ];
    }
}