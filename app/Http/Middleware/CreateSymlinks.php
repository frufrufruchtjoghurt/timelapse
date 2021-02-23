<?php

namespace App\Http\Middleware;

use App\Models\Project;
use App\Models\Symlink;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CreateSymlinks
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $projects = Auth::user()->projects()->get();

        if (Auth::user()->hasAccess('manager') || Auth::user()->hasAccess('admin'))
            $projects = Project::all();

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
                    $symlink->is_latest = str_contains($folder, '/latest');
                    $symlink->save();
                    symlink($path, public_path('img/') . $hash);
                }
            }
        }

        return $next($request);
    }
}
