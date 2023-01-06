<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\Property\InlineSimplePropertyAnnotationRector;
use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->sets([
        LevelSetList::UP_TO_PHP_81,
        SetList::CODE_QUALITY,
        SetList::CODING_STYLE,
        SetList::DEAD_CODE,
        SetList::NAMING,
        SetList::PRIVATIZATION,
        SetList::PSR_4,
        SetList::TYPE_DECLARATION,
    ]);
    $rectorConfig->rule(InlineSimplePropertyAnnotationRector::class);

    $rectorConfig->parallel();
    $rectorConfig->paths([__DIR__ . '/src', __DIR__ . '/tests', __FILE__]);
    $rectorConfig->importNames();
};
