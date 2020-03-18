<?php

namespace Tlr\Phpnum\Frameworks\Laravel;

use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rule;
use InvalidArgumentException;
use ReflectionClass;
use Tlr\Phpnum\Contracts\Enum;

class EnumServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->applyRuleMacros();
    }

    public function applyRuleMacros()
    {
        Rule::macro('enum', function (string $enum) {
            $class = new ReflectionClass($enum);

            /* @var $enum Enum */
            if (! $class->implementsInterface(Enum::class)) {
                throw new InvalidArgumentException(
                    sprintf(
                        'Value passed to Rule::enum must be instance of [%s]. [%s] Given.',
                        Enum::class,
                        $enum
                    )
                );
            }

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
