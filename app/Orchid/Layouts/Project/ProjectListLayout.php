<?php

namespace App\Orchid\Layouts\Project;

use App\Models\Project;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\ModalToggle;
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
                                ->route('platform.projects.edit', $project->id)
                                ->icon('pencil'),

                            Button::make(__('Delete'))
                                ->method('remove')
                                ->confirm(__('Möchten Sie das Project wirklich löschen?'))
                                ->parameters([
                                    'id' => $project->id,
                                ])
                                ->icon('trash'),

                            Button::make(__('Inaktiv setzen'))
                                ->method('changeActiveStatus')
                                ->confirm(__('Möchten Sie das Projekt wirklich aktivieren und für alle Benutzer sichtbar machen?'))
                                ->parameters([
                                    'id' => $project->id,
                                ])
                                ->icon('close')
                                ->canSee(!$project->inactive),

                            Button::make(__('Aktiv setzen'))
                                ->method('changeActiveStatus')
                                ->confirm(__('Möchten Sie das Projekt wirklich deaktivieren und für alle Benutzer unsichtbar machen?'))
                                ->parameters([
                                    'id' => $project->id,
                                ])
                                ->icon('check')
                                ->canSee($project->inactive),

                            ModalToggle::make(__('Inaktivitätsdatum einstellen'))
                                ->modal('inactivityDate')
                                ->method('setInactivityDate')
                                ->parameters([
                                    'id' => $project->id,
                                ])
                                ->icon('clock')
                                ->confirm(__('Wollen Sie dieses Inaktivitätsdatum wirklich einstellen?'))
                                ->canSee($project->inactivity_date == null && !$project->inactive),

                            Button::make(__('Inaktivitätsdatum entfernen'))
                                ->method('removeInactivityDate')
                                ->confirm(__('Möchten Sie das Inaktivitätsdatum wirklich entfernen?'))
                                ->parameters([
                                    'id' => $project->id,
                                ])
                                ->icon('close')
                                ->canSee($project->inactivity_date != null && !$project->inactive),
                        ]);
                }),
        ];
    }
}
