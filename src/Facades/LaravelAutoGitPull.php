<?php

namespace Ahmeti\LaravelAutoGitPull\Facades;

use Illuminate\Support\Facades\Facade;

class LaravelAutoGitPull extends Facade {
    protected static function getFacadeAccessor() { return 'laravel-auto-git-pull'; }
}