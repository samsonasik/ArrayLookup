<?php

declare(strict_types=1);

namespace ArrayLookup\Assert;

use Closure;
use InvalidArgumentException;
use ReflectionFunction;
use ReflectionMethod;
use ReflectionNamedType;
use Webmozart\Assert\Assert;

use function sprintf;

final class Filter
{
    public static function boolean(callable $filter): void
    {
        if ($filter instanceof Closure) {
            $reflection = new ReflectionFunction($filter);
        } elseif (is_object($filter)) {
            $reflection = new ReflectionMethod($filter, '__invoke');
        } else {
            Assert::string($filter);

            if (! str_contains($filter, '::')) {
                $reflection = new ReflectionFunction($filter);
            } else {
                [, $method] = explode('::', $filter);
                $reflection = new ReflectionMethod($filter, $method);
            }
        }

        $returnType = $reflection->getReturnType();

        if (! $returnType instanceof ReflectionNamedType) {
            throw new InvalidArgumentException('Expected a bool return type on callable filter, null given');
        }

        $returnTypeName = $returnType->getName();
        if ($returnTypeName !== 'bool') {
            throw new InvalidArgumentException(sprintf(
                'Expected a bool return type on callable filter, %s given',
                $returnTypeName
            ));
        }
    }
}
