<?php

namespace App\Http\Controllers;

use App\Camera;
use App\Project;
use App\Repair;
use App\System;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CameraController extends Controller
{
  public function index()
  {

  }

    /**
   * Show page for camera creation
   *
   *  @return \Illuminate\Contracts\Support\Renderable
   */
  public function create()
  {
    return view('camera.create');
  }

  public function list()
  {
    return view('camera.list', ['cameras' => DB::table('cameras', 'c')
      ->select('c.id', 'c.model', 'c.serial_nr', 'c.purchase_date', DB::raw('IFNULL(rc.count, 0) as count'))
      ->leftJoinSub(Repair::select('element_id', DB::raw('count(element_id) as count'))
        ->where('type', '=', 'c')->groupBy('element_id'), 'rc', function($join) {
          $join->on('c.id', '=', 'element_id');
      })->get()
    ]);
  }

  public function edit($id)
  {
    try
    {
      $camera = Camera::findOrFail($id);

      return view('camera.edit', ['camera' => $camera]);
    }
    catch(Exception $e)
    {
      error_log($e->getMessage());
      return redirect(route('camera.list'))->with('error', 'Ein Fehler ist aufgetreten! Bitte versuchen Sie es erneut!');
    }
  }

  public function save($id)
  {
    try
    {
      $date = request('purchase_date_c');
      if ($date > date('Y-m-d') || $date < YEAR_MIN)
      {
        return redirect(route('camera.edit', [$id]))->with('error', 'Es wurde kein gültiges Jahr angegeben!');
      }

      Camera::where('id', $id)
        ->update([
          'serial_nr' => request('serial_nr_c'),
          'model' => request('type_c'),
          'purchase_date' => $date
        ]);

      return redirect(route('camera.list'))->with('success', 'Kamera erfolgreich bearbeitet!');
    }
    catch (Exception $e)
    {
      error_log($e);
      return redirect(route('camera.list'))
        ->with('error', 'Ein Fehler ist aufgetreten! Bitte versuchen Sie es erneut!');
    }
  }

  public function store()
  {
    try
    {
      $camera = new Camera();

      $camera->serial_nr = request('serial_nr_c');
      $camera->model = request('type_c');

      $date = request('purchase_date_c');
      if ($date > date('Y-m-d') || $date < YEAR_MIN)
      {
        return redirect(route('camera.create'))->with('error', 'Es wurde kein gültiges Jahr angegeben!');
      }
      $camera->purchase_date = $date;

      $first_match = Camera::where([
        'serial_nr' => $camera->serial_nr,
        'model' => $camera->model,
        'purchase_date' => $camera->purchase_date
      ]);

      if ($first_match->exists())
      {
        return redirect(route('camera.create'))->with('error', 'Eine Kamera mit der Konfiguration wurde bereits angelegt!');
      }

      $camera->save();
      return redirect(route('camera.create'))->with('success', 'Kamera erfolgreich angelegt!');
    }
    catch(Exception $e)
    {
      error_log($e);
      return redirect(route('camera.create'))->with('error', 'Ein unbekannter Fehler ist aufgetreten! Sollte dies öfter passieren, kontaktieren Sie bitte einen Administrator!');
    }
  }

  public function destroy($id)
  {
    if (request('delete') !== 'LÖSCHEN')
    {
      return redirect(route('camera.list'))->with('error', 'Eingabe inkorrekt, Löschvorgang fehlgeschlagen!');
    }
    try
    {
      $camera = Camera::findOrFail($id);

      if (Project::where('cid', '=', $id)->exists())
      {
        return redirect(route('camera.list'))->with('error', 'Die Kamera ist einem Projekt zugewiesen, Löschvorgang fehlgeschlagen!');
      }

      $camera->delete();

      return redirect(route('camera.list'))->with('success', 'Kamera erfolgreich entfernt!');
    }
    catch (Exception $e)
    {
      error_log($e);
      return redirect(route('camera.list'))->with('error', 'Ein Fehler ist aufgetreten! Bitte versuchen Sie es erneut!');
    }
  }
}
