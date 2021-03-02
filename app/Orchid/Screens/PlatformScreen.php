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

        $latestSymlinks = Auth::user()->symlinks()->where('is_latest', '=', true)->get();
        $latestPaths = array();

        foreach ($latestSymlinks as $latestSymlink) {
            $latestFiles = scandir(public_path('img/') . $latestSymlink->symlink, SCANDIR_SORT_ASCENDING);

            foreach ($latestFiles as $latestFile) {
                if (preg_match('/^pic[0-9][0-9][0-9].jpg$/', $latestFile)) {
                    $latestPaths[$latestSymlink->project_id] = 'img/' . $latestSymlink->symlink . '/' . $latestFile;
                    break;
                }
            }
        }

        $features = array();

        foreach ($projects as $project) {
            if (Auth::user()->hasAccess('manager')) {
                $features[$project->id] = ['archive' => false, 'deeplink' => false];
                foreach ($project->features()->get() as $feature) {
                    $features[$project->id]['archive'] |= $feature->archive;
                    $features[$project->id]['deeplink'] |= $feature->deeplink;
                }
                continue;
            } else if (Auth::user()->hasAccess('admin')) {
                $features[$project->id] = ['archive' => true, 'deeplink' => true];
                continue;
            }

            $projFeat = Auth::user()->features()->where('project_id', '=', $project->id)->get()->first();
            $features[$project->id] = ['archive' => $projFeat->archive, 'deeplink' => $projFeat->deeplink];
        }

        return [
            'projects' => $projects,
            'features' => $features,
            'picturePaths' => $latestPaths,
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
