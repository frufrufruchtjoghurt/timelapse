<?php

namespace App\Http\Middleware;

use App\Projectuser;
use App\Role;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckProjectAccess
{
    /**
     * Check if the requesting user is allowed to access a project.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user_role = Role::where('id', Auth::user()->rid)->pluck('name')->first();
        $has_access = Projectuser::where([
            'project_nr' => $request->id,
            'uid' => Auth::user()->id
          ])->exists();

        if (!($has_access || $user_role == 'manager' || $user_role == 'admin'))
        {
          return redirect(route('home'))->with('warning', 'Unzureichende Berechtigungen für Projektzugriff!');
        }

        return $next($request);
    }
}
