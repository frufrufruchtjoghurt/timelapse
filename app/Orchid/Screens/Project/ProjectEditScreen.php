<?php

namespace App\Orchid\Screens\Project;

use App\Models\Camera;
use App\Models\Company;
use App\Models\Feature;
use App\Models\Project;
use App\Models\Projectuser;
use App\Models\System;
use App\Orchid\Layouts\Project\ProjectCompaniesListener;
use App\Orchid\Layouts\Project\ProjectUsersListener;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
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

            foreach (Company::where('id', end($companies))->get()->first()->users()->get() as $cu)
            {
                $companyUsers[$cu->id] = $cu->first_name . " " . $cu->last_name . ", " . $cu->company()->get()->first()->name;
            }

            $user_features = $rawuser->features()->where('pid', $project->id)->get()->first();

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

        return [
            'project' => $project,
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
            Button::make(__('Create project'))
                ->icon('pencil')
                ->method('createOrUpdate')
                ->canSee(!$this->exists),

            Button::make(__('Update'))
                ->icon('note')
                ->method('createOrUpdate')
                ->canSee($this->exists),

            Button::make(__('Remove'))
                ->icon('trash')
                ->method('remove')
                ->canSee($this->exists)
                ->confirm(__('Are you sure you want to delete the project?')),
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
                    ->required(),

                Input::make('project.name')
                    ->title(__('Projektname'))
                    ->type('text')
                    ->required(),

                DateTimer::make('project.start_date')
                    ->title(__('Startdatum'))
                    ->format('Y-m-d')
                    ->required(),
            ])->title(__('Projektdaten')),

            Layout::rows([
                Relation::make('project.cid')
                    ->title(__('Kamera'))
                    ->fromModel(Camera::class, 'id')
                    ->displayAppend('full')
//                    ->applyScope('available')
                    ->required(),

                Relation::make('project.sid')
                    ->title(__('System'))
                    ->fromModel(System::class, 'id')
                    ->displayAppend('full')
//                    ->applyScope('available')
                    ->required(),

                Input::make('project.vpn_ip')
                    ->title(__('VPN-IP-Adresse'))
                    ->mask([
                        'mask' => '999.999.999.999',
                        'groupSeperator' => '.',
                    ])
                    ->required(),
            ])->title(__('Projektausstattung')),

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
            'project.start_date' => 'required|date_format:Y-m-d',
            ]);

        $project->fill($request->get('project'));

        $users = $request->get('users');

        $project->save();

        foreach ($users as $user)
        {
            $p_user = new ProjectUser();
            $p_user->uid = $user;
            $p_user->project_nr = $project->id;
            $p_user->save();

            $features = new Feature();
            $features->fill($request->get($user));
            $features->uid = $user;
            $features->pid = $project->id;
            $features->save();
        }

        Toast::success(__('Projekt wurde erfolgreich gespeichert!'));

        return redirect()->route('platform.projects');
    }
}
