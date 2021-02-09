<?php

namespace App\Console;

use App\Models\Symlink;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

                unlink(public_path('view/') . $symlink->symlink);
                $symlink->delete();
            }
        })->everyFiveMinutes();
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
