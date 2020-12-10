<?php

namespace App\Orchid\Layouts\Project;

use App\Models\Project;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Persona;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class ProjectListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'projects';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): array
    {
        return [
            TD::set('id', __('Projektnummer'))
                ->cantHide()
                ->filter(TD::FILTER_TEXT)
                ->sort(),

            TD::set('name', __('Projektname'))
                ->cantHide()
                ->filter(TD::FILTER_TEXT)
                ->sort(),

            TD::set('cid', __('Kamera'))
                ->render(function (Project $project) {
                    return new Persona($project->camera()->get()->first()->presenter());
                }),

            TD::set('sid', __('System'))
                ->render(function (Project $project) {
                    return new Persona($project->system()->get()->first()->presenter());
                }),

            TD::set('vpn_ip', __('VPN-IP-Adresse'))
                ->sort(),

            TD::set('longitude', __('Längengrad'))
                ->defaultHidden(),

            TD::set('latitude', __('Breitengrad'))
                ->defaultHidden(),

            TD::set('start_date', __('Startdatum'))
                ->cantHide()
                ->sort()
                ->filter(TD::FILTER_DATE)
                ->render(function (Project $project) {
                    if ($project->start_date == null)
                        return __('Nie');
                    return $project->start_date->toDateString();
                }),

            TD::set('end_date', __('Enddatum'))
                ->cantHide()
                ->sort()
                ->filter(TD::FILTER_DATE)
                ->render(function (Project $project) {
                    if ($project->end_date == null)
                        return __('Nie');
                    return $project->end_date->toDateString();
                }),

            TD::set('inactive', __('Status'))
                ->sort()
                ->render(function (Project $project) {
                    return $project->inactive ? __('Inaktiv') : __('Aktiv');
                }),

            TD::set('inactivity_date', __('Inaktivitätsdatum'))
                ->sort()
                ->defaultHidden()
                ->render(function (Project $project) {
                    if ($project->inactivity_date == null)
                        return __('Nie');
                    return $project->inactivity_date->toDateString();
                }),

            TD::set('updated_at', __('Zuletzt geändert'))
                ->sort()
                ->defaultHidden()
                ->render(function (Project $project) {
                    if ($project->updated_at == null)
                        return __('Nie');
                    return $project->updated_at->toDateTimeString();
                }),

            TD::set(__('Actions'))
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(function (Project $project) {
                    return DropDown::make()
                        ->icon('options-vertical')
                        ->list([

                            Link::make(__('Edit'))
                                ->route('platform.projects.edit', $project->project_nr)
                                ->icon('pencil'),

                            Button::make(__('Delete'))
                                ->method('remove')
                                ->confirm(__('Are you sure you want to delete the project?'))
                                ->parameters([
                                    'id' => $project->project_nr,
                                ])
                                ->icon('trash'),
                        ]);
                }),
        ];
    }
}
