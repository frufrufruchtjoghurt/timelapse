<?php

namespace App\Orchid\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Orchid\Filters\Filter;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;

class CompanyFilter extends Filter
{
    /**
     * @var array
     */
    public $parameters = [
        'company',
    ];

    /**
     * @return string
     */
    public function name(): string
    {
        return __('Firma');
    }

    /**
     * @param Builder $builder
     *
     * @return Builder
     */
    public function run(Builder $builder): Builder
    {
        Log::debug($builder);
        return $builder->join('companies', 'users.company_id', '=', 'companies.id')
            ->where('name', 'LIKE', '%'.$this->request->get('name').'%');
    }

    /**
     * @return Field[]
     */
    public function display(): array
    {
        return [
            Input::make('company')
                ->title(__('Firma suchen'))
                ->help(__('Firmenname eingeben')),
        ];
    }
}
