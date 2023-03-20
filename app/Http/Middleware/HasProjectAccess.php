<?php

namespace App\Http\Middleware;

use App\Models\Project;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Orchid\Support\Facades\Alert;

class HasProjectAccess
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
            $projects = Project::query()->get();

        $projectIds = array();

        foreach ($projects as $project)
            $projectIds[] = $project->id;

        if (!in_array($request->id, $projectIds)) {
            Alert::error(__('Sie haben nicht genügend Berechtigungen für diese Aktion!'));
            return redirect()->route('platform.main');
        }

        $userFeatures = array();

        $projFeat = Auth::user()->features()->where('project_id', '=', $request->id)->get()->first();
        if (!empty($projFeat)) {
            $userFeatures = ['archive' => $projFeat->archive, 'deeplink' => $projFeat->deeplink];
        }

        if (Auth::user()->hasAccess('manager') || Auth::user()->hasAccess('admin'))
            $userFeatures = ['archive' => true, 'deeplink' => true];

        if (str_contains($request->url(), 'archive') && (empty($userFeatures) || !$userFeatures['archive'])) {
            Alert::error(__('Sie haben nicht genügend Berechtigungen für diese Aktion!'));
            return redirect()->route('platform.main');
        }

        if (str_contains($request->url(), 'deeplink') && (empty($userFeatures) || !$userFeatures['deeplink'])) {
            Alert::error(__('Sie haben nicht genügend Berechtigungen für diese Aktion!'));
            return redirect()->route('platform.main');
        }

        return $next($request);
    }
}
