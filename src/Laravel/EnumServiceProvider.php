<?php

namespace Tlr\Phpnum\Laravel;

use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rule;

class EnumServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Rule::macro('enum', function (string $enum) {
            return Rule::in($enum::pureValues());
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
