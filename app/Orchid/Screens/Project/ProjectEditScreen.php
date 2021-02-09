<?php

namespace App\Orchid\Screens\Project;

use App\Models\Camera;
use App\Models\Company;
use App\Models\Feature;
use App\Models\Fixture;
use App\Models\Photovoltaic;
use App\Models\Project;
use App\Models\ProjectSystem;
use App\Models\Router;
use App\Models\SupplyUnit;
use App\Models\Ups;
use App\Orchid\Layouts\Project\ProjectCompaniesListener;
use App\Orchid\Layouts\Project\ProjectSystemsListener;
use App\Rules\alphaNumString;
use Carbon\Carbon;
use DateTimeZone;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Orchid\Support\Facades\Alert;
use \Orchid\Support\Facades\Toast;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;

class ProjectEditScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Projekt erstellen';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Details';

    /**
     * @var array
     */
    public $permission = [
        'admin',
        'manager'
    ];

    /**
     * @var bool
     */
    public $exists = false;

    /**
     * Query data.
     *
     * @param SimCard $project
     *
     * @return array
     */
    public function query(Project $project): array
    {
        $this->exists = $project->exists;

        if (!$this->exists) {
            return [
                'project' => $project,
            ];
        }

        $this->name = __('Projekt bearbeiten');

        $rawusers = $project->users()->get();
        $systemIds = $project->supplyUnits()->get();
        $selectedSystems = array();
        $systems = array();
        $users = array();
        $companyUsers = array();
        $features = array();
        $companies = array();

        foreach ($rawusers as $rawuser)
        {
            $users[] = $rawuser->id;

            if (!array_search($rawuser->company()->get()->first()->id, $companies))
            {
                $companies[] = $rawuser->company()->get()->first()->id;
            }

            foreach (Company::query()->where('id', end($companies))->get()->first()->users()->get() as $cu)
            {
                $companyUsers[$cu->id] = $cu->first_name . " " . $cu->last_name . ", " . $cu->company()->get()->first()->name;
            }

            $user_features = $rawuser->features()->where('project_id', $project->id)->get()->first();

            $features[$rawuser->id] = Layout::rows([
                Group::make([
                    CheckBox::make($rawuser->id . '.archive')
                        ->title(__('Archiv'))
                        ->value($user_features->archive)
                        ->sendTrueOrFalse(),

                    CheckBox::make($rawuser->id . '.deeplink')
                        ->title(__('DeepLink'))
                        ->value($user_features->deeplink)
                        ->sendTrueOrFalse(),

                    CheckBox::make($rawuser->id . '.storage_medium')
                        ->title(__('Speichermedium'))
                        ->value($user_features->storage_medium)
                        ->sendTrueOrFalse(),
                ]),
            ])->title($companyUsers[$rawuser->id]);
        }

        $tz = new DateTimeZone('Europe/Vienna');

        $storedSystems = SupplyUnit::query()
            ->leftJoin('project_systems as s', 's.supply_unit_id', '=', 'supply_units.id')
            ->leftJoin('projects as p', 'p.id', '=', 's.project_id')
            ->join('cameras as c', 'c.supply_unit_id', '=' , 'supply_units.id')
            ->select('supply_units.*', 'c.model', 'c.name')->get();
        $activeSystems = SupplyUnit::query()
            ->leftJoin('project_systems as s', 's.supply_unit_id', '=', 'supply_units.id')
            ->leftJoin('projects as p', 'p.id', '=', 's.project_id')
            ->join('cameras as c', 'c.supply_unit_id', '=' , 'supply_units.id')
            ->whereBetween('p.rec_end_date', [now($tz), $project->start_date])
            ->select('supply_units.*', 'c.model', 'c.name')->get();
        $datesQuery = SupplyUnit::query()
            ->leftJoin('project_systems as s', 's.supply_unit_id', '=', 'supply_units.id')
            ->leftJoin('projects as p', 'p.id', '=', 's.project_id')
            ->join('cameras as c', 'c.supply_unit_id', '=' , 'supply_units.id')
            ->whereBetween('p.rec_end_date', [now($tz), $project->start_date])
            ->select('supply_units.id', 'p.rec_end_date');

        $tmpArray = $this->getAvailableSystems($storedSystems, $activeSystems, $datesQuery);

        foreach ($tmpArray as $key => $item) {
            $selectedSystems[$key] = $item;
        }

        foreach ($systemIds as $id) {
            $systems[] = $id->id;
        }

        return [
            'project' => $project,
            'availableSystems' => $selectedSystems,
            'systems' => $systems,
            'companies' => $companies,
            'features' => $features,
            'companyUsers' => $companyUsers,
            'users' => $users,
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
            Button::make(__('Projekt erstellen'))
                ->icon('pencil')
                ->method('createOrUpdate')
                ->canSee(!$this->exists),

            Button::make(__('Änderungen speichern'))
                ->icon('note')
                ->method('createOrUpdate')
                ->canSee($this->exists),
        ];
    }

    public function asyncGetSystems($start_date)
    {
        $tz = new DateTimeZone('Europe/Vienna');

        $storedSystems = SupplyUnit::query()
            ->leftJoin('project_systems as s', 's.supply_unit_id', '=', 'supply_units.id')
            ->leftJoin('projects as p', 'p.id', '=', 's.project_id')
            ->join('cameras as c', 'c.supply_unit_id', '=' , 'supply_units.id')
            ->where('p.id', '=', null)
            ->orWhere('p.rec_end_date', '<', now($tz))
            ->select('supply_units.*', 'c.model', 'c.name')->get();
        $activeSystems = SupplyUnit::query()
            ->leftJoin('project_systems as s', 's.supply_unit_id', '=', 'supply_units.id')
            ->leftJoin('projects as p', 'p.id', '=', 's.project_id')
            ->join('cameras as c', 'c.supply_unit_id', '=' , 'supply_units.id')
            ->whereBetween('p.rec_end_date', [now($tz), $start_date])
            ->select('supply_units.*', 'c.model', 'c.name')->get();
        $datesQuery = SupplyUnit::query()
            ->leftJoin('project_systems as s', 's.supply_unit_id', '=', 'supply_units.id')
            ->leftJoin('projects as p', 'p.id', '=', 's.project_id')
            ->join('cameras as c', 'c.supply_unit_id', '=' , 'supply_units.id')
            ->whereBetween('p.rec_end_date', [now($tz), $start_date])
            ->select('supply_units.id', 'p.rec_end_date');

        return [
            'availableSystems' => $this->getAvailableSystems($storedSystems, $activeSystems, $datesQuery),
        ];
    }

    public function asyncGetUsers($companies, $users)
    {
        $companyUsers = null;

        foreach ($companies as $company)
        {
            $company = Company::where('id', $company)->get()->first();

            foreach ($company->users()->get() as $cu)
            {
                $companyUsers[$cu->id] = $cu->first_name . " " . $cu->last_name . ", " . $company->name;
            }
        }

        if ($companyUsers == null)
        {
            $companyUsers[0] = __('Es existieren für diese Firmen keine Kunden.');
        }

        if (!empty($users))
        {
            $selUsers = array();
            $userFeatures = array();

            foreach ($users as $user)
            {
                $selUsers[$user] = $companyUsers[$user];

                $userFeatures[$user] = Layout::rows([
                    Group::make([
                        CheckBox::make($user . '.archive')
                            ->title(__('Archiv'))
                            ->sendTrueOrFalse(),

                        CheckBox::make($user . '.deeplink')
                            ->title(__('DeepLink'))
                            ->sendTrueOrFalse(),

                        CheckBox::make($user . '.storage_medium')
                            ->title(__('Speichermedium'))
                            ->sendTrueOrFalse(),
                    ]),
                ])->title($companyUsers[$user]);
            }

            return [
                'users' => $selUsers,
                'companyUsers' => $companyUsers,
                'features' => $userFeatures,
            ];
        }
        return [
            'companyUsers' => $companyUsers,
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
            Layout::rows([
                Input::make('project.id')
                    ->title(__('Projektnummer'))
                    ->type('number')
                    ->value(Project::all()->max('id') + 1)
                    ->required(),

                Input::make('project.name')
                    ->title(__('Projektname'))
                    ->type('text')
                    ->required(),

                DateTimer::make('project.start_date')
                    ->title(__('Startdatum'))
                    ->format('Y-m-d')
                    ->required(),

                DateTimer::make('project.rec_end_date')
                    ->title(__('Aufnahmeenddatum'))
                    ->format('Y-m-d')
                    ->required(),
            ])->title(__('Projektdaten')),

            ProjectSystemsListener::class,

            Layout::rows([
                Relation::make('companies.')
                    ->fromModel(Company::class, 'name')
                    ->multiple()
                    ->required(),
            ])->title('Kundenfirmen'),

            ProjectCompaniesListener::class,
        ];
    }

    public function createOrUpdate(Project $project, Request $request)
    {
        $request->validate([
            'project.id' => 'unique:App\Models\Project,id',
            'project.name' => ['required', new alphaNumString()],
            'project.start_date' => 'required|date_format:Y-m-d',
            'project.rec_end_date' => 'required|date_format:Y-m-d',
            ]);

        $project->fill($request->get('project'));

        $users = $request->get('users');
        $systems = $request->get('systems');

        Storage::disk('systems')->makeDirectory(sprintf('P%04d-%s', $project->id, $project->name));

        $project->save();

        $systemsPath = base_path('../../systems/');
        $activeSystems = SupplyUnit::query()
            ->leftJoin('project_systems as s', 's.supply_unit_id', '=', 'supply_units.id')
            ->leftJoin('projects as p', 'p.id', '=', 's.project_id')
            ->join('cameras as c', 'c.supply_unit_id', '=' , 'supply_units.id')
            ->whereBetween('p.rec_end_date', [Carbon::now(), $project->start_date])
            ->select('supply_units.*', 'c.model', 'c.name')->get();

        foreach ($systems as $system) {
            $projectSystem = new ProjectSystem();
            $projectSystem->supply_unit_id = $system;
            $projectSystem->project_id = $project->id;
            $projectSystem->save();

            $supplyUnit = SupplyUnit::query()->firstWhere('id', '=', $system);
            $cameras = $supplyUnit->cameras()->get();

            foreach ($cameras as $camera) {
                $projPath = sprintf('P%04d-%s/%s', $project->id, $project->name, $camera->name);
                Storage::disk('systems')->makeDirectory($projPath);
            }
        }

        foreach ($users as $user)
        {
            $features = new Feature();
            $features->fill($request->get($user));
            $features->user_id = $user;
            $features->project_id = $project->id;
            $features->save();
        }

        Toast::success(__('Projekt wurde erfolgreich gespeichert!'));

        return redirect()->route('platform.projects');
    }

    /**
     * @param $storedSystems
     * @param $activeSystems
     * @param $datesQuery
     * @return array
     */
    private function getAvailableSystems($storedSystems, $activeSystems, $datesQuery)
    {
        $availableSystems = array();

        foreach ($storedSystems as $storedSystem) {
            if ($storedSystem->model == null)
                continue;

            if (empty($availableSystems) || !array_key_exists($storedSystem->id, $availableSystems)) {
                $availableSystems[$storedSystem->id] = '';
                $this->collectSystemDesc($storedSystem, $availableSystems);
            }

            $availableSystems[$storedSystem->id] .= ', ' . $storedSystem->model . ' (' . $storedSystem->name . ')';
        }

        foreach ($activeSystems as $activeSystem) {
            if ($activeSystem->model == null)
                continue;

            if (empty($availableSystems) || !array_key_exists($activeSystem->id, $availableSystems)) {
                $date = $datesQuery->where('supply_units.id', '=', $activeSystem->id)
                    ->max('p.rec_end_date');
                $availableSystems[$activeSystem->id] = '[WARNUNG] System noch bis ' . $date . ' aktiv! | ';
                $this->collectSystemDesc($activeSystem, $availableSystems);
            }

            $availableSystems[$activeSystem->id] .= ', ' . $activeSystem->model . ' (' . $activeSystem->name . ')';
        }

        if (empty($availableSystems)) {
            $availableSystems[0] = __('Es konnte für das gegebene Startdatum leider kein System gefunden werden');
        }

        return $availableSystems;
    }

    /**
     * @param $system
     * @param $array
     */
    private function collectSystemDesc($system, &$array) {
        $router = Router::query()->where('id', '=', $system->router_id)->get()->first();
        $array[$system->id] .= 'Gehäuse: ' . Fixture::query()->where('id', '=', $system->fixture_id)->get()->first()->model
            . ', Router: ' . $router->model . ' (' . $router->simCard()->get()->first()->contract . ')';

        if ($system->ups_id != null) {
            $array[$system->id] .= ", USV: " . Ups::query()->where('id', '=', $system->ups_id)->get()->first()->model;
        }

        if ($system->heating) {
            $array[$system->id] .= ", Heizung";
        }

        if ($system->cooling) {
            $array[$system->id] .= ", Lüftung";
        }

        if ($system->photovoltaik_id != null) {
            $array[$system->id] .= ", " . Photovoltaic::query()->where('id', '=', $system->photovoltaic_id)->get()->first()->model;
        }
    }
}
