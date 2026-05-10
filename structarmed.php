<?php

declare(strict_types=1);

use Boundwize\StructArmed\Architecture;
use Boundwize\StructArmed\Preset\Preset;

return Architecture::define()
    ->cacheDirectory(is_dir('/tmp') ? '/tmp/structarmed' : null)
    ->withPreset(Preset::PSR4());