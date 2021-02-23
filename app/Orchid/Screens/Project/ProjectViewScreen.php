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

        $userSymlinks = Auth::user()->symlinks()
            ->where('project_id', '=', $project->id)
            ->where('is_latest', '=', false)->get();
        $picturePaths = array();

        foreach ($userSymlinks as $userSymlink) {
            $symlink = $userSymlink->symlink;
            $dirs = scandir(public_path('img/') . $symlink, SCANDIR_SORT_DESCENDING);
            $latestDir = 'img/' . $symlink . '/' . $dirs[0];

            if (str_contains($dirs[0], '.php'))
                $latestDir = 'img/' . $symlink . '/' . $dirs[1];

            $pictures = scandir(public_path($latestDir), SCANDIR_SORT_DESCENDING);
            $latestPicture = $latestDir . '/' . $pictures[0];

            if (str_contains($pictures[0], '.php'))
                $latestPicture = 'img/' . $symlink . '/' . $pictures[1];

            $picturePaths[] = $latestPicture;
        }

        $movieSymlinks = Auth::user()->symlinks()
            ->where('project_id', '=', $project->id)
            ->where('is_latest', '=', true)->get();
        $moviePaths = array();

        foreach ($movieSymlinks as $movieSymlink) {
            $movies = scandir(public_path('img/') . $movieSymlink->symlink, SCANDIR_SORT_ASCENDING);

            foreach ($movies as $movie) {
                if (!strcmp($movie, '.') || !strcmp($movie, '..') || str_contains($movie, '.php'))
                    continue;

                $moviePaths[] = 'img/' . $movieSymlink->symlink . '/' . $movie;
            }
        }

        return [
            'project' => $project,
            'picturePaths' => $picturePaths,
            'moviePaths' => $moviePaths,
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
