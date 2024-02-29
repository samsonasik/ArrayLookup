<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\PHPUnit\Set\PHPUnitSetList;

return RectorConfig::configure()
    ->withPhpSets(php81: true)
    ->withPreparedSets(
        codeQuality: true,
        codingStyle: true,
        deadCode: true,
        naming: true,
        privatization: true,
        typeDeclarations: true
    )
    ->withSets([
        PHPUnitSetList::PHPUNIT_100,
    ])
    ->withParallel()
    ->withPaths([__DIR__ . '/src', __DIR__ . '/tests', __FILE__])
    ->withImportNames();
