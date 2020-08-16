<?php

namespace App\Http\Controllers;

use App\Camera;
use Exception;
use Illuminate\Http\Request;

define('YEAR_MIN', date('Y-m-d', strtotime('January 01 2000')));

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

  public function store()
  {
    try
    {
      $camera = new Camera();

      $camera->serial_nr = request('serial_nr_c');
      $camera->model = request('type_c');

      setlocale(LC_TIME, ['de_at', 'de_de', 'de']);
      $year = request('build_year_c');
      if ($year > strftime('%Y') || $year < YEAR_MIN)
      {
        return redirect(route('camera.create'))->with('error', 'Es wurde kein gültiges Jahr angegeben!');
      }
      $camera->build_year = $year;

      $first_match = Camera::where([
        'serial_nr' => $camera->serial_nr,
        'model' => $camera->model,
        'build_year' => $camera->build_year
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
}
