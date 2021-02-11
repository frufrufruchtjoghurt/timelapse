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
        if (Auth::user()->symlinks()->get()->isEmpty()) {
            $projects = Auth::user()->projects()->get();
            foreach ($projects as $project) {
                $folders = Storage::disk('systems')->directories(sprintf('P%04d-%s', $project->id, $project->name));
                foreach ($folders as $folder) {
                    $path = Storage::disk('systems')->path($folder);
                    $hash = str_replace('.', Str::random(1),
                        str_replace('$', Str::random(1),
                        str_replace('/', Str::random(1), bcrypt($path))));
                    $symlink = new Symlink();
                    $symlink->user_id = Auth::user()->id;
                    $symlink->project_id = $project->id;
                    $symlink->symlink = $hash;
                    $symlink->is_movies = str_contains($folder, '/movies');
                    $symlink->save();
                    symlink($path, public_path('view/') . $hash);
                }
            }
        }

        $userSymlinks = Auth::user()->symlinks()->where('is_movies', '=', false)->get();
        $picturePaths = array();
        $moviePaths = array();

        foreach ($userSymlinks as $userSymlink) {
            $symlink = $userSymlink->symlink;
            $latestDir = 'view/' . $symlink . '/' . scandir(public_path('view/') . $symlink, SCANDIR_SORT_DESCENDING)[0];
            $latestPicture = $latestDir . '/' . scandir(public_path($latestDir), SCANDIR_SORT_DESCENDING)[0];

            $picturePaths[] = $latestPicture;
        }

        $movieSymlink = Auth::user()->symlinks()->where('is_movies', '=', true)->get()->first();

        foreach (scandir(public_path('view/') . $movieSymlink->symlink,
            SCANDIR_SORT_ASCENDING) as $movie) {
            if (!strcmp($movie, '.') || !strcmp($movie, '..'))
                continue;

            $moviePaths[] = 'view/' . $movieSymlink->symlink . '/' . $movie;
        }

        return [
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
        return [];
    }
}
