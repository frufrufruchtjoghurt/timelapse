<?php

namespace App\Orchid\Screens\Project;

use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;

class ProjectArchiveScreen extends Screen
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
    public $description = 'Archiv';

    /**
     * Query data.
     *
     * @return array
     */
    public function query($id): array
    {
        $project = Auth::user()->projects()->where('projects.id', '=', $id)->get()->first();

        if (Auth::user()->hasAccess('manager') || Auth::user()->hasAccess('admin'))
            $project = Project::query()->where('projects.id', '=', $id)->get()->first();

        $this->name = $project->name;

        $userSymlinks = Auth::user()->symlinks()
            ->where('project_id', '=', $project->id)
            ->where('is_movies', '=', false)->get();
        $picturePaths = array();
        $symlinks = array();

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

            $symlinks[] = 'img/' . $symlink;
            $picturePaths[$symlink] = $latestPicture;
        }

        return [
            'picturePaths' => $picturePaths,
            'symlinks' => $symlinks,
        ];
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): array
    {
        return [];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): array
    {
        return [
            Layout::view('project.archive'),
        ];
    }
}
