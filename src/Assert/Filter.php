<?php

declare(strict_types=1);

namespace ArrayLookup\Assert;

use Closure;
use InvalidArgumentException;
use ReflectionFunction;
use ReflectionMethod;
use ReflectionNamedType;

use function gettype;
use function is_object;
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
            throw new InvalidArgumentException(
                sprintf('Expected Closure or invokable object on callable filter, %s given', gettype($filter))
            );
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
