<?php

namespace App\Console;

use App\Models\Address;
use App\Models\Camera;
use App\Models\Company;
use App\Models\Project;
use App\Models\Symlink;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;

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
            $symlinks = Symlink::query()->where('is_persistent', '=', false)->get();
            $sessionUsers = DB::table('sessions')->select('user_id')->distinct()->get();

            foreach ($symlinks as $symlink) {
                if ($sessionUsers->contains('user_id', '=', $symlink->user_id))
                    continue;

                unlink(public_path('img/') . $symlink->symlink);
                $symlink->delete();
            }

            $img_symlinks = scandir(public_path('img'));

            foreach ($img_symlinks as $img_symlink) {
                if (preg_match('/^.*\..*$/', $img_symlink))
                    continue;

                if (!Symlink::query()->where('symlink', '=', $img_symlink)->exists())
                    unlink(public_path('img'). '/' . $img_symlink);
            }
        })->everyFiveMinutes();

        $schedule->call(function () {
            foreach (Address::all() as $address) {
                if (!Company::query()->where('address_id', '=', $address->id)->exists())
                    $address->delete();
            }
        })->everyThirtyMinutes();

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
                    while ($pos < count($days) &&
                            !preg_match('/^[2-9][0-9]-((1[0-2])|(0[1-9]))-(([0-2][0-9])|(3[0-1]))$/', $days[$pos])) {
                        $pos++;
                    }

                    if ($pos == count($days))
                        continue;

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
                    } else {
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
