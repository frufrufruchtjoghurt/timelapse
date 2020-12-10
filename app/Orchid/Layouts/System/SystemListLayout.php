<?php

namespace App\Orchid\Layouts\System;

use App\Models\System;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Persona;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class SystemListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'systems';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): array
    {
        return [
            TD::set('model', __('Gehäuse'))
                ->filter(TD::FILTER_TEXT)
                ->cantHide()
                ->render(function (System $system) {
                    return new Persona($system->fixture()->get()->first()->presenter());
                }),

            TD::set('model', __('Router'))
                ->filter(TD::FILTER_TEXT)
                ->cantHide()
                ->render(function (System $system) {
                    return new Persona($system->router()->get()->first()->presenter());
                }),

            TD::set('model', __('Sim-Karten'))
                ->filter(TD::FILTER_TEXT)
                ->cantHide()
                ->render(function (System $system) {
                    return new Persona($system->sim_card()->get()->first()->presenter());
                }),

            TD::set('model', __('USV'))
                ->filter(TD::FILTER_TEXT)
                ->cantHide()
                ->render(function (System $system) {
                    return new Persona($system->ups()->get()->first()->presenter());
                }),

            TD::set('model', __('Heizungen'))
                ->filter(TD::FILTER_TEXT)
                ->cantHide()
                ->render(function (System $system) {
                    if ($system->heating()->exists())
                        return new Persona($system->heating()->get()->first()->presenter());

                    return __('Keine');
                }),

            TD::set('model', __('Photovoltaikanlagen'))
                ->filter(TD::FILTER_TEXT)
                ->cantHide()
                ->render(function (System $system) {
                    if ($system->photovoltaic()->exists())
                        return new Persona($system->photovoltaic()->get()->first()->presenter());

                    return __('Keine');
                }),

            TD::set('project_nr', __('Projekt'))
                ->render(function (System $system) {
                    $project = $system->projects()->where('end_date', null)->get()->first();
                    return empty($project) ? __('Keines') : $project->id;
                }),

            TD::set(__('Actions'))
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(function (System $system) {
                    return DropDown::make()
                        ->icon('options-vertical')
                        ->list([

                            Link::make(__('Edit'))
                                ->route('platform.systems.edit', $system->id)
                                ->icon('pencil'),

                            Button::make(__('Delete'))
                                ->method('remove')
                                ->confirm(__('Are you sure you want to delete the system?'))
                                ->parameters([
                                    'id' => $system->id,
                                ])
                                ->icon('trash'),
                        ]);
                }),
        ];
    }
}
