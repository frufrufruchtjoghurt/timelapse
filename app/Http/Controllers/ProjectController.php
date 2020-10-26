<?php

namespace App\Http\Controllers;

use App\Camera;
use App\Company;
use App\Fixture;
use App\Heating;
use App\Photovoltaic;
use App\Project;
use App\Projectuser;
use App\Repair;
use App\Router;
use App\SimCard;
use App\System;
use App\Ups;
use App\User;
use ArrayObject;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
  /**
   * Show the project monitor.
   *
   * @return \Illuminate\Contracts\Support\Renderable
   */
  public function index()
  {
    return view('project.index');
  }

  /**
   * Go to the project creator
   *
   *  @return \Illuminate\Contracts\Support\Renderable
   */
  public function create()
  {
    // Check for cached project creation data
    if (Cache::has('project') && Cache::has('cid'))
    {
      return redirect(route('project.users', ['cid' => Cache::get('cid'), 'users' => User::all()]))
        ->with('warning', 'Ein Projekt wurde noch nicht vollständig erstellt! Bitte fügen Sie die fehlenden Daten hinzu oder brechen Sie den Erstellvorgang ab!');
    }
    // Delete cache in case of missing data
    else if (Cache::has('project') && !Cache::has('cid'))
    {
      Cache::forget('project');
      return view('project.create', ['companies' => Company::all()])
        ->with('warning', 'Beim Speichern des letzten Projekts ist ein Fehler aufgetreten, bitte überprüfen Sie die Daten des zuletzt erstellten Projekts!');
    }
    else if (!Cache::has('project') && Cache::has('cid'))
    {
      Cache::forget('cid');
      return view('project.create', ['companies' => Company::all()])
        ->with('warning', 'Beim Speichern des letzten Projekts ist ein Fehler aufgetreten, bitte überprüfen Sie die Daten des zuletzt erstellten Projekts!');
    }
    return view('project.create', ['companies' => Company::all()]);
  }

  public function users(Request $request)
  {
    $project = new Project;

    $request->validate([
      'name' => 'required|alpha_dash',
      'project_nr' => 'required|numeric'
    ]);

    $project->project_nr = request('project_nr');
    $project->name = request('name');

    error_log($project->project_nr);

    try
    {
      if (Project::where('project_nr', $project->project_nr)->exists())
      {
        return redirect(route('project.create'))->with('error', 'Ein Projekt mit der angegebenen Projektnummer existiert bereits!');
      }
    }
    catch (Exception $e)
    {
      error_log($e->getMessage());
      return redirect(route('project.create'))->with('error', 'Bei einer Überprüfung der Projektnummer ist ein Fehler aufgetreten!');
    }

    try
    {
      $cids = $request->cid;

      if(!$cids)
      {
        return redirect(route('project.create', ['companies' => Company::all()]))
        ->with('error', 'Bitte wählen Sie mindestens eine Firma aus!');
      }

      $systems = DB::table('systems as y')
        ->select('y.id as id', 'f.id as id_f', 'f.model as model_f', DB::raw('IFNULL(rc_f.count, 0) as count_f'),
          'r.id as id_r', 'r.model as model_r', DB::raw('IFNULL(rc_r.count, 0) as count_r'), 's.id as id_s',
          's.contract as model_s', DB::raw('IFNULL(rc_s.count, 0) as count_s'), 'u.id as id_u', 'u.model as model_u',
          DB::raw('IFNULL(rc_u.count, 0) as count_u'), 'h.id as id_h', 'h.model as model_h',
          DB::raw('IFNULL(rc_h.count, 0) as count_h'), 'p.id as id_p', 'p.model as model_p',
          DB::raw('IFNULL(rc_p.count, 0) as count_p'), 'j.inactivity_date as inactivity_date')
        ->join('fixtures as f', 'f.id', '=', 'y.fixture_id')
        ->leftJoinSub(Repair::select('element_id', DB::raw('count(element_id) as count'))
          ->where('type', '=', 'f')->groupBy('element_id'), 'rc_f', function($join) {
            $join->on('f.id', '=', 'element_id');
        })
        ->join('routers as r', 'r.id', '=', 'y.router_id')
        ->leftJoinSub(Repair::select(DB::raw('count(element_id) as count'))
          ->where('type', '=', 'r')->groupBy('element_id'), 'rc_r', function($join) {
            $join->on('r.id', '=', 'element_id');
        })
        ->join('sim_cards as s', 's.id', '=', 'y.sim_id')
        ->leftJoinSub(Repair::select(DB::raw('count(element_id) as count'))
          ->where('type', '=', 's')->groupBy('element_id'), 'rc_s', function($join) {
            $join->on('s.id', '=', 'element_id');
        })
        ->join('ups as u', 'u.id', '=', 'y.ups_id')
        ->leftJoinSub(Repair::select(DB::raw('count(element_id) as count'))
          ->where('type', '=', 'u')->groupBy('element_id'), 'rc_u', function($join) {
            $join->on('u.id', '=', 'element_id');
        })
        ->leftJoin('heatings as h', 'h.id', '=', 'y.heating_id')
        ->leftJoinSub(Repair::select(DB::raw('count(element_id) as count'))
          ->where('type', '=', 'h')->groupBy('element_id'), 'rc_h', function($join) {
            $join->on('h.id', '=', 'element_id');
        })
        ->leftJoin('photovoltaics as p', 'p.id', '=', 'y.photovoltaic_id')
        ->leftJoinSub(Repair::select(DB::raw('count(element_id) as count'))
          ->where('type', '=', 'p')->groupBy('element_id'), 'rc_p', function($join) {
            $join->on('p.id', '=', 'element_id');
        })
        ->leftJoin('projects as j', 'j.sid', '=', 'y.id')
        ->whereDate('j.inactivity_date', '<=', date('Y-m-d'))
        ->orWhereNull('j.project_nr')
        ->where('r.storage', '=', 0)
        ->get();

      $cameras = DB::table('cameras as c')
        ->select('c.id', 'c.model', 'c.serial_nr', 'c.purchase_date', DB::raw('IFNULL(rc.count, 0) as count'),
          'j.inactivity_date')
        ->leftJoinSub(Repair::select('element_id', DB::raw('count(element_id) as count'))
          ->where('type', '=', 'c')->groupBy('element_id'), 'rc', function($join) {
            $join->on('c.id', '=', 'element_id');
          })
        ->leftJoin('projects as j', 'j.cid', '=', 'c.id')
        ->whereDate('j.inactivity_date', '<=', date('Y-m-d'))
        ->orWhereNull('j.project_nr')
        ->where('c.storage', '=', 0)
        ->get();
    }
    catch (Exception $e)
    {
      error_log($e->getMessage());
      return redirect(route('project.create', ['companies' => Company::all()]))
        ->with('error', 'Ein Fehler ist aufgetreten! Bitte versuchen Sie es erneut!');
    }

    try
    {
      Cache::put('project', $project, now()->addMinutes(10));
      Cache::put('cid', $cids, now()->addMinutes(10));
      return view('project.users', [
        'cids' => Cache::get('cid'),
        'users' => User::all(),
        'systems' => $systems,
        'cameras' => $cameras
        ]);
    }
    catch (Exception $e)
    {
      Cache::forget('project');
      Cache::forget('cid');
      error_log($e->getMessage());
      return redirect(route('project.create'))->with('error', 'Beim Speichern des Projekts ist ein Fehler aufgetreten!');
    }
  }

  public function store(Request $request)
  {
    if (!Cache::has('project') || !Cache::has('cid'))
    {
      Cache::forget('project');
      Cache::forget('cid');
      return redirect(route('project.create', ['companies' => Company::all()]))
        ->with('warning', 'Beim Speichern des Projekts ist ein Fehler aufgetreten, bitte erstellen Sie das Projekt erneut!');
    }

    $project = Cache::get('project');
    try
    {
      $user_ids = $request->users;
      $system_id = request('system');

      if (!$system_id)
      {
        return redirect(route('project.users', ['cid' => Cache::get('cid'), 'users' => User::all()]))
          ->with('error', 'Bitte wählen Sie ein System aus!');
      }
    }
    catch (Exception $e)
    {
      error_log($e->getMessage());
      return redirect(route('project.users', ['cid' => Cache::get('cid'), 'users' => User::all()]))
        ->with('error', 'Bitte wählen Sie mindestens einen Kunden aus!');
    }
    $project_user_cache = array();

    try
    {
      $archive = $request->archive;
      $deeplink = $request->deeplink;
      $datastore = $request->datastore;

      foreach ($user_ids as $user_id)
      {
        $project_user = new Projectuser;

        $project_user->pid = $project->project_nr;
        $project_user->uid = $user_id;

        // Check the selected features for each user
        $project_user->archive = in_array($user_id, $archive);
        $project_user->deeplink = in_array($user_id, $deeplink);
        $project_user->storage_medium = in_array($user_id, $datastore);

        $project_user_cache[] = $project_user;
      }
      Cache::put('project_users', $project_user_cache, now()->addMinutes(10));
    }
    catch (Exception $e)
    {
      Cache::forget('project_users');
      error_log($e->getMessage());
      return redirect(route('project.create'))->with('error', 'Beim Speichern der Projektkunden ist ein Fehler aufgetreten!');
    }

    try
    {
      $date = request('date');

      $camera_id = request('camera');

      if (!$camera_id)
      {
        return redirect(route('project.users', ['cid' => Cache::get('cid'), 'users' => User::all()]))
          ->with('error', 'Bitte wählen Sie eine Kamera aus!');
      }

      $project->cid = $camera_id;
      $project->sid = $system_id;
      $project->start_date = $date;

      $pid = $project->project_nr;

      $project->save();

      foreach ($project_user_cache as $project_user)
      {
        $project_user->save();
      }

      // Update camera storage
      Camera::where('id', $camera_id)
        ->update(['storage' => $pid]);

      // Get system and update component storage
      $system = System::where('id', $system_id)->first();
      Fixture::where('id', $system->fixture_id)
        ->update(['storage' => $pid]);
      Router::where('id', $system->router_id)
        ->update(['storage' => $pid]);
      SimCard::where('id', $system->sim_id)
        ->update(['storage' => $pid]);
      Ups::where('id', $system->ups_id)
        ->update(['storage' => $pid]);
      Heating::where('id', $system->heating_id)
        ->update(['storage' => $pid]);
      Photovoltaic::where('id', $system->photovoltaic_id)
        ->update(['storage' => $pid]);

    }
    catch (Exception $e)
    {
      error_log($e->getMessage());
      return redirect(route('project.create'))
        ->with('error', 'Ein Fehler ist aufgetreten! Bitte versuchen Sie es erneut');
    }

    Cache::forget('project');
    Cache::forget('cid');
    Cache::forget('project_users');
    return redirect(route('project.create'))->with('success', 'Projekt und Projektkunden erfolgreich gespeichert!');
  }

  public function list()
  {
    return view('project.list', ['projects' => DB::table('projects', 'p')
      ->select('p.project_nr', 'p.name', 'p.start_date', 'p.end_date', 'p.invisible', 's.name as system',
        'c.model as camera')
      ->join('cameras as c', 'c.id', '=', 'p.cid')
      ->join('systems as s', 's.id', '=', 'p.sid')
      ->get()
      ]);
  }

  public function show($id)
  {
    try
    {
      $project = Project::findOrFail($id);
      $projectusers = Projectuser::where('pid', $id)->get();
      return view('project.show', ['project' => $project, 'users' => $projectusers]);
    }
    catch (Exception $e)
    {
      error_log($e->getMessage());
      return redirect(route('project.list'))->with('error', 'Ein Fehler ist aufgetreten!');
    }
  }
}
