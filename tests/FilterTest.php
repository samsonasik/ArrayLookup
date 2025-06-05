<?php

declare(strict_types=1);

namespace ArrayLookup\Tests;

use ArrayLookup\AtLeast;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class FilterTest extends TestCase
{
    public function testOnceWithFilterInvokableClass(): void
    {
        $data   = [1, 2, 3];
        $filter = new class {
            public function __invoke(int $datum): bool
            {
                return $datum === 1;
            }
        };

        $this->assertTrue(AtLeast::once($data, $filter));
    }

    public function testOnceWithStringFilter(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected Closure or invokable object on callable filter, string given');

        $data   = [1, 'f'];
        $filter = 'is_string';

        AtLeast::once($data, $filter);
    }

    public function testWithoutReturnTypeCallable(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected a bool return type on callable filter, null given');

        $data = [1, 2, 3];

        // phpcs:disable
        $filter = new class {
            public function __invoke(int $datum)
            {
                return $datum === 1;
            }
        };
        // phpcs:enable

        AtLeast::once($data, $filter);
    }

    public function testWithNonBoolReturnTypeCallable(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected a bool return type on callable filter, string given');

        $data   = [1, 2, 3];
        $filter = new class {
            public function __invoke(int $datum): string
            {
                return 'test';
            }
        };

        AtLeast::once($data, $filter);
    }

    public function testWithUnionReturnTypeCallable(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected a bool return type on callable filter, string|bool given');

        $data   = [1, 2, 3];
        $filter = new class {
            public function __invoke(int $datum): string|bool
            {
                return 'test';
            }
        };

        AtLeast::once($data, $filter);
    }

    public function testWithIntersectionTypeCallable(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'Expected a bool return type on callable filter, ArrayLookup\Tests\A&ArrayLookup\Tests\B given'
        );

        $data   = [1, 2, 3];
        $filter = new class {
            public function __invoke(int $datum): A&B
            {
            }
        };

        AtLeast::once($data, $filter);
    }
}
