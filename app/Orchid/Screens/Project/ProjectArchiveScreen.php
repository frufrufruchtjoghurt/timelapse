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
            ->where('is_latest', '=', false)
            ->where('is_persistent', '=', false)->get();
        $picturePaths = array();
        $symlinks = array();

        foreach ($userSymlinks as $userSymlink) {
            $symlink = $userSymlink->symlink;
            $dirs = scandir(public_path('img/') . $symlink, SCANDIR_SORT_DESCENDING);
            $pos = 0;
            while ($pos < count($dirs) &&
                !preg_match('/^2[0-9]-((1[0-2])|(0[1-9]))-(([0-2][0-9])|(3[0-1]))$/', $dirs[$pos])) {
                $pos++;
            }
            if ($pos == count($dirs))
                continue;

            $latestDir = 'img/' . $symlink . '/' . $dirs[$pos];
            $pictures = scandir(public_path($latestDir), SCANDIR_SORT_DESCENDING);
            $pos = 0;
            while ($pos < count($pictures) &&
                !preg_match('/^image.*\.jpg$/', $pictures[$pos])) {
                $pos++;
            }
            if ($pos == count($pictures))
                continue;

            $latestPicture = $latestDir . '/' . $pictures[$pos];

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
