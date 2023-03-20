<?php

namespace App\Orchid\Layouts\Project;

use App\Models\Project;
use App\Models\SupplyUnit;
use Illuminate\Support\Facades\Log;
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
            TD::make('id', __('Projektnummer'))
                ->cantHide()
                ->filter(TD::FILTER_TEXT)
                ->sort(),

            TD::make('name', __('Projektname'))
                ->cantHide()
                ->filter(TD::FILTER_TEXT)
                ->sort(),

            TD::make('cid', __('Kamera'))
                ->render(function (Project $project) {
                    $text = '';
                    foreach ($project->cameras() as $camera) {
                        $text .= $camera->model . ' (' . $camera->name . '), ';
                    }
                    return $text;
                }),

            TD::make('sid', __('Versorgungseinheit'))
                ->defaultHidden()
                ->render(function (Project $project) {
                    $ids = $project->supplyUnits()->get();
                    $text = '';
                    foreach ($ids as $id) {
                        $text .= SupplyUnit::query()->where('id', '=', $id->id)->get()->first()->getFullAttribute();
                    }
                    return $text;
                }),

            TD::make('start_date', __('Startdatum'))
                ->cantHide()
                ->sort()
                ->filter(TD::FILTER_DATE)
                ->render(function (Project $project) {
                    if ($project->start_date == null)
                        return __('Nie');
                    return $project->start_date->toDateString();
                }),

            TD::make('rec_end_date', __('Film-Enddatum'))
                ->cantHide()
                ->sort()
                ->filter(TD::FILTER_DATE)
                ->render(function (Project $project) {
                    return $project->rec_end_date->toDateString();
                }),

            TD::make('inactive', __('Status'))
                ->sort()
                ->render(function (Project $project) {
                    return $project->inactive ? __('Inaktiv') : __('Aktiv');
                }),

            TD::make('inactivity_date', __('Inaktivitätsdatum'))
                ->sort()
                ->defaultHidden()
                ->render(function (Project $project) {
                    if ($project->inactivity_date == null)
                        return __('Nie');
                    return $project->inactivity_date->toDateString();
                }),

            TD::make('updated_at', __('Zuletzt geändert'))
                ->sort()
                ->defaultHidden()
                ->render(function (Project $project) {
                    if ($project->updated_at == null)
                        return __('Nie');
                    return $project->updated_at->toDateTimeString();
                }),

            TD::make(__('Actions'))
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(function (Project $project) {
                    return DropDown::make()
                        ->icon('options-vertical')
                        ->list([

                            Link::make(__('Bearbeiten'))
                                ->route('platform.projects.edit', $project->id)
                                ->icon('pencil'),

                            Button::make(__('Löschen'))
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
