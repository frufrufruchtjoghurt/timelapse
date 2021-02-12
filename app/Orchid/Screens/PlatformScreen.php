<?php

declare(strict_types=1);

namespace App\Orchid\Screens;

use App\Models\Project;
use App\Models\Symlink;
use App\Orchid\Layouts\DashboardLayout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Contracts\Cardable;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;

class PlatformScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Dashboard';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Willkommen im Timelapse-Kundenportal!';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        $projects = Auth::user()->projects()->get();

        if (Auth::user()->hasAccess('manager') || Auth::user()->hasAccess('admin'))
            $projects = Project::all();

        $userSymlinks = Auth::user()->symlinks()->where('is_movies', '=', false)->get();
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

            $picturePaths[$userSymlink->project_id][] = $latestPicture;
        }

        $movieSymlinks = Auth::user()->symlinks()->where('is_movies', '=', true)->get();
        $moviePaths = array();

        foreach ($movieSymlinks as $movieSymlink) {
            $movies = scandir(public_path('img/') . $movieSymlink->symlink, SCANDIR_SORT_ASCENDING);

            foreach ($movies as $movie) {
                if (!strcmp($movie, '.') || !strcmp($movie, '..') || str_contains($movie, '.php'))
                    continue;

                $moviePaths[$movieSymlink->project_id] = 'img/' . $movieSymlink->symlink . '/' . $movie;
            }
        }

        return [
            'projects' => $projects,
            'picturePaths' => $picturePaths,
            'moviePaths' => $moviePaths,
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
     * @return \Orchid\Screen\Layout[]
     */
    public function layout(): array
    {
        return [
            Layout::view('project.dashboard'),
        ];
    }
}
