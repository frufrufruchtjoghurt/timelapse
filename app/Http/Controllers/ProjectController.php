<?php

namespace App\Http\Controllers;

use App\Company;
use App\Project;
use App\Projectuser;
use App\User;
use ArrayObject;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;

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

  public function usersSelector()
  {
    $project = new Project;

    $project->project_nr = \request('project_nr');
    $project->name = \request('name');

    try
    {
      if (Project::where('project_nr', $project->project_nr)->exists())
      {
        return \redirect(route('project.create'))->with('error', 'Ein Projekt mit der angegebenen Projektnummer existiert bereits!');
      }
    }
    catch (Exception $e)
    {
      \error_log($e->getMessage());
      return \redirect(route('project.create'))->with('error', 'Bei einer Überprüfung der Projektnummer ist ein Fehler aufgetreten!');
    }

    try
    {
      $cids = $_GET['cid'];
    }
    catch (Exception $e)
    {
      return redirect(route('project.create', ['companies' => Company::all()]))
        ->with('error', 'Bitte wählen Sie mindestens eine Firma aus!');
    }

    try
    {
      Cache::put('project', $project, now()->addMinutes(30));
      Cache::put('cids', $cids);
      return \view('project.users', ['cids' => Cache::get('cids'), 'users' => User::all()]);
    }
    catch (Exception $e)
    {
      Cache::forget('project');
      Cache::forget('cid');
      \error_log($e->getMessage());
      return \redirect(route('project.create'))->with('error', 'Beim Speichern des Projekts ist ein Fehler aufgetreten!');
    }
  }

  public function store()
  {
    if (!Cache::has('project') || !Cache::has('cid'))
    {
      Cache::forget('project');
      Cache::forget('cid');
      return redirect(route('project.create', ['companies' => Company::all()]))
        ->with('warning', 'Beim Speichern des Projekts ist ein Fehler aufgetreten, bitte erstellen Sie das Projekt erneut!');
    }

    $project_nr = Cache::get('project')->project_nr;
    try
    {
      $user_ids = $_POST['users'];
    }
    catch (Exception $e)
    {
      return redirect(route('project.users', ['cid' => Cache::get('cid'), 'users' => User::all()]))
        ->with('error', 'Bitte wählen Sie mindestens einen Kunden aus!');
    }
    $project_user_cache = array();

    try
    {
      foreach ($user_ids as $user_id)
      {
        $project_user = new Projectuser;

        $project_user->project_nr = $project_nr;
        $project_user->uid = $user_id;

        $project_user_cache[] = $project_user;
      }
      Cache::put('project_users', $project_user_cache, now()->addMinutes(30));
    }
    catch (Exception $e)
    {
      Cache::forget('project_users');
      \error_log($e->getMessage());
      return \redirect(route('project.create'))->with('error', 'Beim Speichern der Projektkunden ist ein Fehler aufgetreten!');
    }

    Cache::forget('project');
    Cache::forget('cid');
    Cache::forget('project_users');
    return \redirect(\route('project.create'))->with('success', 'Projekt und Projektkunden erfolgreich gespeichert!');
  }
}
