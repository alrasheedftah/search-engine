<?php

namespace ana\searchEngine\Facades;

use Illuminate\Support\Facades\Facade;

class SearchEngine extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() {
        return 'ana\searchEngine\searchEngine';
    }
}