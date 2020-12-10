<?php

namespace App\Orchid\Filters;

use Illuminate\Database\Eloquent\Builder;
use Orchid\Filters\Filter;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;

class UserFilter extends Filter
{
    /**
     * @var array
     */
    public $parameters = [
        'name',
    ];

    /**
     * @return string
     */
    public function name(): string
    {
        return __('Benutzer');
    }

    /**
     * @param Builder $builder
     *
     * @return Builder
     */
    public function run(Builder $builder): Builder
    {
        return $builder->where('first_name', 'LIKE', '%'.$this->request->get('name').'%')
            ->orWhere('first_name', 'LIKE', '%'.$this->request->get('name').'%');
    }

    /**
     * @return Field[]
     */
    public function display(): array
    {
        return [
            Input::make('name')
            ->title(__('Name suchen'))
            ->help(__('Vor- oder Nachname eingeben')),
        ];
    }
}
