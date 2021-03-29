<?php

namespace App\Orchid\Screens\Project;

use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Orchid\Screen\Action;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;

class ProjectViewScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = '';

    /**
     * Display header description.
     *
     * @var string|null
     */
    public $description = 'Detailansicht';

    /**
     * Query data.
     *
     * @return array|RedirectResponse
     */
    public function query($id)
    {
        $project = Auth::user()->projects()->where('projects.id', '=', $id)->get()->first();

        if (Auth::user()->hasAccess('manager') || Auth::user()->hasAccess('admin'))
            $project = Project::query()->where('projects.id', '=', $id)->get()->first();

        $this->name = $project->name;

        $symlink = Auth::user()->symlinks()
            ->where('project_id', '=', $project->id)
            ->where('is_latest', '=', true)->get()->first();
        $picturePaths = array();
        $moviePaths = array();

        $files = scandir(public_path('img/') . $symlink->symlink, SCANDIR_SORT_DESCENDING);

        foreach ($files as $file) {
            if (preg_match('/^pic[0-9][0-9][0-9].jpg$/', $file)) {
                $picturePaths[] = sprintf('img/%s/%s', $symlink->symlink, $file);
            } else if (preg_match('/^mov[0-9][0-9][0-9]\..*$/', $file)) {
                $moviePaths[] = sprintf('img/%s/%s', $symlink->symlink, $file);
            }
        }

        return [
            'project' => $project,
            'picturePaths' => $picturePaths,
            'moviePaths' => $moviePaths,
            'songs' => $project->songs()->get(),
        ];
    }

    /**
     * Button commands.
     *
     * @return Action[]
     */
    public function commandBar(): array
    {
        return [];
    }

    /**
     * Views.
     *
     * @return Layout[]|string[]
     */
    public function layout(): array
    {
        return [
            Layout::view('project.view')
        ];
    }
}
