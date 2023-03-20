<?php

namespace App\Orchid\Layouts\Camera;

use App\Models\Camera;
use App\Models\Company;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Persona;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class CameraListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'cameras';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): array
    {
        return [
            TD::make('model', __('Kamera'))
                ->sort()
                ->cantHide()
                ->filter(TD::FILTER_TEXT)
                ->render(function (Camera $camera) {
                   return new Persona($camera->presenter());
                }),

            TD::make('purchase_date', __('Kaufdatum'))
                ->sort()
                ->filter(TD::FILTER_DATE)
                ->render(function (Camera $camera) {
                    return $camera->purchase_date->toDateString();
                }),

            TD::make('updated_at', __('Zuletzt Bearbeitet'))
                ->sort()
                ->render(function (Camera $camera) {
                    if ($camera->updated_at == null)
                    {
                        return __('Nie');
                    }
                    return $camera->updated_at->toDateTimeString();
                }),

            TD::make('broken', __('Beschädigt'))
                ->sort()
                ->render(function (Camera $camera) {
                    return $camera->broken ? __('Ja') : __('Nein');
                }),

            TD::make(__('Actions'))
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(function (Camera $camera) {
                    return DropDown::make()
                        ->icon('options-vertical')
                        ->list([

                            Link::make(__('Edit'))
                                ->route('platform.cameras.edit', $camera->id)
                                ->icon('pencil'),

                            Button::make(__('Delete'))
                                ->method('remove')
                                ->confirm(__('Are you sure you want to delete the company?'))
                                ->parameters([
                                    'id' => $camera->id,
                                ])
                                ->icon('trash'),

                            Button::make(__('Beschädigt'))
                                ->method('changeBrokenStatus')
                                ->confirm(__('Are you sure you want to label this camera as broken?'))
                                ->parameters([
                                    'id' => $camera->id,
                                ])
                                ->icon('wrench')
                                ->canSee(!$camera->broken),

                            Button::make(__('Unbeschädigt'))
                                ->method('changeBrokenStatus')
                                ->confirm(__('Are you sure you want to label this camera usable?'))
                                ->parameters([
                                    'id' => $camera->id,
                                ])
                                ->icon('check')
                                ->canSee($camera->broken),
                        ]);
                }),
        ];
    }
}
