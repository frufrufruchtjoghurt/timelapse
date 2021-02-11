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

        if (Auth::user()->symlinks()->get()->isEmpty()) {
            foreach ($projects as $project) {
                $folders = Storage::disk('systems')->directories(sprintf('P%04d-%s', $project->id, $project->name));
                foreach ($folders as $folder) {
                    if (str_contains($folder, '.php'))
                        continue;

                    $path = Storage::disk('systems')->path($folder);
                    $hash = Str::random(50);
                    $symlink = new Symlink();
                    $symlink->user_id = Auth::user()->id;
                    $symlink->project_id = $project->id;
                    $symlink->symlink = $hash;
                    $symlink->is_movies = str_contains($folder, '/movies');
                    $symlink->save();
                    symlink($path, public_path('img/') . $hash);
                }
            }
        }

        $userSymlinks = Auth::user()->symlinks()->where('is_movies', '=', false)->get();
        $picturePaths = array();
        $moviePaths = array();

        foreach ($userSymlinks as $userSymlink) {
            $symlink = $userSymlink->symlink;
            $latestDir = 'img/' . $symlink . '/' . scandir(public_path('img/') . $symlink, SCANDIR_SORT_DESCENDING)[0];
            $latestPicture = $latestDir . '/' . scandir(public_path($latestDir), SCANDIR_SORT_DESCENDING)[0];

            $picturePaths[$userSymlink->project_id][] = $latestPicture;
        }

        $movieSymlinks = Auth::user()->symlinks()->where('is_movies', '=', true)->get();

        foreach ($movieSymlinks as $movieSymlink) {
            $movies = scandir(public_path('img/') . $movieSymlink->symlink, SCANDIR_SORT_ASCENDING);

            foreach ($movies as $movie) {
                if (!strcmp($movie, '.') || !strcmp($movie, '..'))
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
