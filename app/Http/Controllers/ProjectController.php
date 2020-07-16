<?php

namespace App\Http\Controllers;

use App\Company;
use App\Project;
use App\Projectuser;
use App\User;
use Exception;
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
      $project->save();
      return \view('project.users', ['cid' => \request('cid'), 'users' => User::all(), 'project_nr' => $project->project_nr]);
    }
    catch (Exception $e)
    {
      Project::where('project_nr', $project->project_nr)->delete();
      \error_log($e->getMessage());
      return \redirect(route('project.create'))->with('error', 'Beim Speichern des Projekts ist ein Fehler aufgetreten!');
    }
  }

  public function store()
  {
    $project_nr = \request('project_nr');
    $user_ids = \request('users');

    try
    {
      foreach ($user_ids as $user_id)
      {
        $projectuser = new Projectuser;

        $projectuser->project_nr = $project_nr;
        $projectuser->uid = $user_id;

        $projectuser->save();
      }
    }
    catch (Exception $e)
    {
      Project::where('project_nr', $project_nr)->delete();
      \error_log($e->getMessage());
      return \redirect(route('project.create'))->with('error', 'Beim Speichern der Projektkunden ist ein Fehler aufgetreten!');
    }

    return \redirect(\route('project.create'))->with('success', 'Projekt und Projektkunden erfolgreich gespeichert!');
  }
}
