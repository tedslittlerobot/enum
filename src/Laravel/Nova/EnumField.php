<?php

namespace Tlr\Phpnum\Laravel\Nova;

use Illuminate\Validation\Rule;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;

class EnumField extends Select
{
    /**
     * The enum class name
     *
     * @var string
     */
    protected $enum;

    /**
     * Set the enum
     *
     * @param  string $enum
     *
     * @return EnumField
     */
    public function enum(string $enum)
    {
        $this->enum = $enum;

        return $this
            ->options(array_flip($enum::friendlyNames()))
            ->displayUsing(function ($value) {
                return $value->friendlyName();
            })
        ;
    }

    /**
     * Get the validation rules for this field.
     *
     * @param NovaRequest $request
     * @return array
     */
    public function getRules(NovaRequest $request)
    {
        return array_merge_recursive(parent::getRules($request), [
            $this->attribute => [
                Rule::enum($this->enum),
            ],
        ]);
    }
}
