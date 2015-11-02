<?php

namespace Riari\Forum\Frontend\Support\Facades;

use Illuminate\Support\Facades\Facade;

class Forum extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'forum';
    }
}
