<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Schema::defaultStringLength(191);

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('valid_email', function ($attribute, $value, $parameters, $validator) {
            $dog_char_pos = strpos($value,'@');
            $dot_char_pos = strrpos($value,'.');
            if(!$dog_char_pos || !$dot_char_pos)
                return false;
            if($dog_char_pos<$dot_char_pos && $dot_char_pos-$dog_char_pos>1)
                return true;
            else
                return false;
        });

        if(app()->runningInConsole())
        {
            $mainPath = database_path('migrations');
            $directories = glob($mainPath . '/*' , GLOB_ONLYDIR);
            $paths = array_merge([$mainPath], $directories);

            $this->loadMigrationsFrom($paths);
        }
    }
}
