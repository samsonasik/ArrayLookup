<?php

declare(strict_types=1);

namespace ArrayLookup\Assert;

use Closure;
use InvalidArgumentException;
use ReflectionFunction;
use ReflectionIntersectionType;
use ReflectionMethod;
use ReflectionNamedType;
use ReflectionUnionType;
use Webmozart\Assert\Assert;

use function array_map;
use function gettype;
use function implode;
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

        if ($returnType instanceof ReflectionUnionType || $returnType instanceof ReflectionIntersectionType) {
            $separator = $returnType instanceof ReflectionUnionType ? '|' : '&';
            $types     = $returnType->getTypes();

            Assert::allIsInstanceOf($types, ReflectionNamedType::class);

            throw new InvalidArgumentException(
                sprintf(
                    'Expected a bool return type on callable filter, %s given',
                    implode($separator, array_map(
                        static fn (ReflectionNamedType $reflectionNamedType): string => $reflectionNamedType->getName(),
                        $types
                    ))
                )
            );
        }

        if (! $returnType instanceof ReflectionNamedType) {
            throw new InvalidArgumentException('Expected a bool return type on callable filter, mixed given');
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
