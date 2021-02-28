<?php

namespace App\Console;

use App\Models\Camera;
use App\Models\Project;
use App\Models\Symlink;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
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
            $systems_path = base_path('../../systems');

            foreach ($projects as $project) {
                $project_path = $systems_path . sprintf('/P%04d-%s', $project->id, $project->name);
                $latest_content = scandir($project_path . '/latest');

                if (count(preg_grep('/^pic[0-9][0-9][0-9].jpg$/', $latest_content)) && $project->is_dismantled)
                    continue;

                foreach (preg_grep('/^cam[0-9][0-9][0-9]$/',
                    scandir($project_path))
                    as $dir) {
                    $cam_path = sprintf('%s/%s', $project_path, $dir);
                    $camera = Camera::query()->where('name', '=', $dir)->get()->first();

                    $days = scandir($cam_path, SCANDIR_SORT_DESCENDING);
                    $pos = 0;
                    while (!preg_match('/^2[0-1]-((1[0-2])|(0[1-9]))-(([0-2][0-9])|(3[0-1]))$/', $days[$pos])) {
                        $pos++;
                    }

                    $latest_day = sprintf('%s/%s', $cam_path, $days[$pos]);

                    if (!count(preg_grep(sprintf('/^pic%03d\.jpg$/', explode('m', $camera->name)[1]),
                        $latest_content))) {
                        $pics = scandir($latest_day, SCANDIR_SORT_DESCENDING);
                        $pos = 0;
                        while (!preg_match('/^image.*\.jpg$/', $pics[$pos])) {
                            $pos++;
                        }
                        $latest_pic = $latest_day . '/' . $pics[$pos];

                        symlink($latest_pic, sprintf('%s/latest/pic%03d.jpg', $project_path,
                            explode('m', $camera->name)[1]));

                        $camera->filemtime = filemtime($latest_pic);
                    } else if ($camera->projects() != null &&
                        $camera->projects()->where('video_editor_send_date', null)->get()->first()->id == $project->id &&
                        Carbon::createFromTimestamp(filemtime(sprintf('%s/.', $latest_day)))
                            ->isAfter(Carbon::createFromTimestamp( $camera->filemtime))) {
                        unlink(sprintf('%s/latest/pic%03d.jpg', $project_path,
                            explode('m', $camera->name)[1]));
                        $pics = scandir($latest_day, SCANDIR_SORT_DESCENDING);
                        $pos = 0;
                        while (!preg_match('/^image.*\.jpg$/', $pics[$pos])) {
                            $pos++;
                        }
                        $latest_pic = $latest_day . '/' . $pics[$pos];

                        symlink($latest_pic, sprintf('%s/latest/pic%03d.jpg', $project_path,
                            explode('m', $camera->name)[1]));

                        $camera->filemtime = filemtime($latest_pic);
                    }

                    $camera->save();
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
