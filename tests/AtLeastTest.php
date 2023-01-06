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
}
