<?php

namespace App\Orchid\Layouts\Company;

use App\Models\Company;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Persona;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class CompanyListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'companies';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): array
    {
        return [
            TD::make('name', __('Firma'))
                ->sort()
                ->cantHide()
                ->filter(TD::FILTER_TEXT)
                ->render(function (Company $company) {
                    return new Persona($company->presenter());
                }),

            TD::make('address', __('Address'))
                ->filter(TD::FILTER_TEXT)
                ->render(function (Company $company) {
                    return $company->address()->get()->first()->getFullAttribute();
                }),

            TD::make('updated_at', __('Zuletzt Bearbeitet'))
                ->sort()
                ->render(function (Company $company) {
                    if ($company->updated_at == null)
                    {
                        return __('Nie');
                    }
                    return $company->updated_at->toDateTimeString();
                }),

            TD::make(__('Actions'))
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(function (Company $company) {
                    return DropDown::make()
                        ->icon('options-vertical')
                        ->list([

                            Link::make(__('Edit'))
                                ->route('platform.companies.edit', $company->id)
                                ->icon('pencil'),

                            Button::make(__('Delete'))
                                ->method('remove')
                                ->confirm(__('Are you sure you want to delete the company?'))
                                ->parameters([
                                    'id' => $company->id,
                                ])
                                ->icon('trash'),
                    ]);
            }),
        ];
    }
}
