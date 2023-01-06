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
}