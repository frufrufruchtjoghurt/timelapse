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
                    $symlink->symlink = $hash;
                    $symlink->save();
                    symlink($path, public_path('view/') . $hash);
                }
            }
        }
        return [];
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
