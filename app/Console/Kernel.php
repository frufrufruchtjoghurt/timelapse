<?php

namespace App\Console;

use App\Models\Camera;
use App\Models\Project;
use App\Models\Symlink;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            $symlinks = Symlink::all();
            $sessionUsers = DB::table('sessions')->select('user_id')->distinct()->get();

            foreach ($symlinks as $symlink) {
                if ($sessionUsers->contains('user_id', '=', $symlink->user_id))
                    continue;

                unlink(public_path('img/') . $symlink->symlink);
                $symlink->delete();
            }
        })->everyFiveMinutes();

        $schedule->call(function () {
            $projects = Project::all();

            foreach ($projects as $project) {
                $latest_content = Storage::disk('systems')->allFiles(sprintf('P%04d-%s/latest', $project->id, $project->name));

                if (in_array(preg_match('/^pic[0-9][0-9][0-9]$/', $latest_content), $latest_content) && $project->is_dismantled)
                    continue;

                foreach (Storage::disk('systems')->allFiles(sprintf('P%04d-%s', $project->id, $project->name))
                    as $dir) {
                    if (!preg_match('/^cam[0-9][0-9][0-9]$/', $dir))
                        continue;

                    $cam_path = Storage::disk('systems')->url(sprintf('P%04d-%s/%s',
                        $project->id, $project->name, $dir));
                    $camera = Camera::query()->where('name', '=', $dir)->get()->first();
                }
            }
        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
