<?php

declare(strict_types=1);

namespace App\Models;

/*
* Testing Service Providers...
*/

class ModelServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {
        $this->app->bind(
            'Illuminate\Database\Eloquent\Model',
            'App\Models\Customer'
        );
    }
}
