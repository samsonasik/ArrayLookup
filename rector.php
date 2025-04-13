<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\ClassMethod\BoolReturnTypeFromBooleanStrictReturnsRector;

return RectorConfig::configure()
    ->withPhpSets(php82: true)
    ->withPreparedSets(
        codeQuality: true,
        codingStyle: true,
        deadCode: true,
        naming: true,
        privatization: true,
        typeDeclarations: true,
        phpunitCodeQuality: true
    )
    ->withComposerBased(phpunit: true)
    ->withSkip([
        BoolReturnTypeFromBooleanStrictReturnsRector::class => [
            __DIR__ . '/tests/FilterTest.php',
        ],
    ])
    ->withParallel()
    ->withRootFiles()
    ->withPaths([__DIR__ . '/src', __DIR__ . '/tests'])
    ->withImportNames();
