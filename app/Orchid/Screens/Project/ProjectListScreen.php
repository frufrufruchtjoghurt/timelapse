<?php

namespace App\Orchid\Screens\Project;

use App\Models\Feature;
use App\Models\Project;
use App\Models\ProjectSystem;
use App\Models\SupplyUnit;
use App\Orchid\Layouts\Project\ProjectListLayout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Layouts\Modal;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class ProjectListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Projekte';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Liste aller Projekte';

    /**
     * @var array
     */
    public $permission = [
        'admin',
        'manager'
    ];

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'projects' => Project::filters()
                            ->defaultSort('id')
                            ->paginate(),
        ];
    }

    /**
     * Button commands.
     *
     * @return Action[]
     */
    public function commandBar(): array
    {
        return [
            Link::make(__('Erstellen'))
                ->icon('pencil')
                ->route('platform.projects.edit')
        ];
    }

    /**
     * Views.
     *
     * @return Layout[]
     */
    public function layout(): array
    {
        return [
            Layout::modal('inactivityDate', [
                Layout::rows([
                    DateTimer::make('project.inactivityDate')
                        ->title('Datum')
                        ->required()
                        ->allowInput()
                        ->format('Y-m-d'),
                ]),
            ])->title(__('Inaktivitätsdatum einstellen'))
                ->applyButton(__('Save'))
                ->closeButton(__('Close'))
                ->size(Modal::SIZE_SM),

            ProjectListLayout::class,
        ];
    }

    public function setInactivityDate(Request $request)
    {
        $request->validate([
            'project.inactivityDate' => 'required|date_format:Y-m-d|after:today',
        ]);

        $project = Project::findOrFail($request->get('id'));

        $project->inactivity_date = $request->get('project.inactivityDate');
        $project->save();

        Toast::success(__('Inaktivitätsdatum wurde erstellt!'));
    }

    public function removeInactivityDate(Request $request)
    {
        $project = Project::findOrFail($request->get('id'));

        $project->inactivity_date = null;
        $project->save();

        Toast::success(__('Inaktivitätsdatum wurde erfolgreich entfernt!'));
    }

    public function changeActiveStatus(Request $request)
    {
        $project = Project::findOrFail($request->get('id'));

        $project->inactive = !$project->inactive;
        $project->save();

        Toast::success(__('Status erfolgreich geändert!'));
    }

    public function remove(Request $request)
    {
        $project = Project::query()->where('id', '=', $request->get('id'))->firstOrFail();

        if (!$project->inactive)
        {
            Alert::error(__('Ein aktives Projekt kann nicht gelöscht werden!'));
        }
        else
        {
            foreach ($project->supplyUnits() as $id) {
                $supplyUnit = SupplyUnit::query()->firstWhere('id', '=', $id);
                $cameras = $supplyUnit->cameras()->get();

                foreach ($cameras as $camera) {
                    Storage::disk('systems')->delete($camera->name);
                    Storage::disk('systems')->move($camera->name . '.orig', $camera->name);
                }
            }

            Storage::disk('systems')->deleteDirectory(sprintf('P%04d_%s', $project->id, $project->name));

            Feature::query()->where('project_id', $project->id)->delete();
            ProjectSystem::query()->where('project_id', $project->id)->delete();

            $project->delete();

            Toast::success(__('Projekt wurde gelöscht!'));
        }
    }
}
