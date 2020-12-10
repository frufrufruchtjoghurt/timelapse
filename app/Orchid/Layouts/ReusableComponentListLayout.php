<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Persona;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class ReusableComponentListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target;

    private $name;

    public function __construct(string $target, string $name)
    {
        $this->target = $target;
        $this->name = $name;
    }

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): array
    {
        return [
            TD::set('model', __($this->name))
                ->sort()
                ->cantHide()
                ->filter(TD::FILTER_TEXT)
                ->render(function ($component) {
                    return new Persona($component->presenter());
                }),

            TD::set('purchase_date', __('Kaufdatum'))
                ->sort()
                ->filter(TD::FILTER_DATE)
                ->render(function ($component) {
                    return $component->purchase_date->format('Y-m-d');
                }),

            TD::set('updated_at', __('Zuletzt Bearbeitet'))
                ->sort()
                ->render(function ($component) {
                    if ($component->updated_at == null)
                    {
                        return __('Nie');
                    }
                    return $component->updated_at->format('Y-m-d H:i:s');
                }),

            TD::set('broken', __('Beschädigt'))
                ->sort()
                ->render(function ($component) {
                    return $component->broken ? __('Ja') : __('Nein');
                }),

            TD::set('project_nr', __('Projekt'))
                ->render(function ($component) {
                    $project = $component->projects()->where('end_date', null)->get()->first();
                    return empty($project) ? __('Keines') : $project->id;
                }),

            TD::set(__('Actions'))
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(function ($component) {
                    return DropDown::make()
                        ->icon('options-vertical')
                        ->list([

                            Link::make(__('Edit'))
                                ->route('platform.cameras.edit', $component->id)
                                ->icon('pencil'),

                            Button::make(__('Delete'))
                                ->method('remove')
                                ->confirm(__('Are you sure you want to delete the company?'))
                                ->parameters([
                                    'id' => $component->id,
                                ])
                                ->icon('trash'),

                            Button::make(__('Beschädigt'))
                                ->method('changeBrokenStatus')
                                ->confirm(__('Are you sure you want to label this camera as broken?'))
                                ->parameters([
                                    'id' => $component->id,
                                ])
                                ->icon('wrench')
                                ->canSee(!$component->broken),

                            Button::make(__('Unbeschädigt'))
                                ->method('changeBrokenStatus')
                                ->confirm(__('Are you sure you want to label this camera usable?'))
                                ->parameters([
                                    'id' => $component->id,
                                ])
                                ->icon('check')
                                ->canSee($component->broken),
                        ]);
                }),
        ];
    }
}
