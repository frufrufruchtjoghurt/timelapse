<?php

namespace App\Orchid\Layouts\SupplyUnit;

use App\Models\SupplyUnit;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Persona;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class SupplyUnitListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'supplyunits';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): array
    {
        return [
            TD::make('model', __('Gehäuse'))
                ->filter(TD::FILTER_TEXT)
                ->cantHide()
                ->render(function (SupplyUnit $supplyunit) {
                    return new Persona($supplyunit->fixture()->get()->first()->presenter());
                }),

            TD::make('model', __('Router'))
                ->filter(TD::FILTER_TEXT)
                ->cantHide()
                ->render(function (SupplyUnit $supplyunit) {
                    return new Persona($supplyunit->router()->get()->first()->presenter());
                }),

            TD::make('model', __('USV'))
                ->filter(TD::FILTER_TEXT)
                ->cantHide()
                ->render(function (SupplyUnit $supplyunit) {
                    if ($supplyunit->ups()->exists())
                        return new Persona($supplyunit->ups()->get()->first()->presenter());

                    return __('Keine');
                }),

            TD::make('has_heating', __('Heizungen'))
                ->cantHide()
                ->render(function (SupplyUnit $supplyunit) {
                    if ($supplyunit->has_heating) {
                        return 'Ja';
                    }
                    return 'Nein';
                }),

            TD::make('has_cooling', __('Lüftung'))
                ->cantHide()
                ->render(function (SupplyUnit $supplyunit) {
                    if ($supplyunit->has_cooling) {
                        return 'Ja';
                    }
                    return 'Nein';
                }),

            TD::make('model', __('Photovoltaikanlagen'))
                ->filter(TD::FILTER_TEXT)
                ->cantHide()
                ->render(function (SupplyUnit $supplyunit) {
                    if ($supplyunit->photovoltaic()->exists())
                        return new Persona($supplyunit->photovoltaic()->get()->first()->presenter());

                    return __('Keine');
                }),

            TD::make('project_nr', __('Projekt'))
                ->render(function (SupplyUnit $supplyunit) {
                    $project = $supplyunit->projects()->where('rec_end_date')->get()->first();
                    return empty($project) ? __('Im Lager') : $project->id;
                }),

            TD::make(__('Aktionen'))
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(function (SupplyUnit $supplyunit) {
                    return DropDown::make()
                        ->icon('options-vertical')
                        ->list([

                            Link::make(__('Bearbeiten'))
                                ->route('platform.supplyunits.edit', $supplyunit->id)
                                ->icon('pencil'),

                            Button::make(__('Löschen'))
                                ->method('remove')
                                ->confirm(__('Wollen Sie die Versorgungseinheit wirklich löschen?'))
                                ->parameters([
                                    'id' => $supplyunit->id,
                                ])
                                ->icon('trash'),
                        ]);
                }),
        ];
    }
}
