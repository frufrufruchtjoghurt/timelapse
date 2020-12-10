<?php

namespace App\Orchid\Screens\Project;

use App\Models\Project;
use App\Orchid\Layouts\Project\ProjectListLayout;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
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
            Link::make(__('Create new'))
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
            ProjectListLayout::class,
        ];
    }

    public function remove(Request $request)
    {
        $project = Project::findOrFail($request->get('id'));

        if (!$project->inactive)
        {
            Alert::error(__('Unable to delete active project!'));
        }
        else
        {
            $project->delete();

            Toast::success(__('Project has been deleted!'));
        }
    }
}
