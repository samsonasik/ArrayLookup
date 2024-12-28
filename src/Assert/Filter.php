<?php

declare(strict_types=1);

namespace ArrayLookup\Assert;

use InvalidArgumentException;
use ReflectionFunction;
use ReflectionMethod;
use ReflectionNamedType;
use TypeError;

use function sprintf;

final class Filter
{
    public static function boolean(callable $filter): void
    {
        try {
            $reflection = new ReflectionFunction($filter);
        } catch (TypeError) {
            $reflection = new ReflectionMethod($filter, '__invoke');
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
