<?php

namespace App\Http\Controllers;

use App\Ups;
use Exception;
use Illuminate\Http\Request;

class UpsController extends Controller
{
  public function index()
  {

  }

    /**
   * Show page for UPS creation
   *
   *  @return \Illuminate\Contracts\Support\Renderable
   */
  public function create()
  {
    return view('system.ups.create');
  }

  public function store()
  {
    try
    {
      $ups = new Ups();

      $ups->serial_nr = request('serial_nr_u');
      $ups->model = request('type_u');

      setlocale(LC_TIME, ['de_at', 'de_de', 'de']);
      $year = request('build_year_u');
      if ($year > strftime('%Y') || $year < YEAR_MIN)
      {
        return redirect(route('system.ups.create'))->with('error', 'Es wurde kein gültiges Jahr angegeben!');
      }
      $ups->build_year = $year;

      $first_match = Ups::where([
        'serial_nr' => $ups->serial_nr,
        'model' => $ups->model,
        'build_year' => $ups->build_year
      ]);

      if ($first_match->exists())
      {
        return redirect(route('system.ups.create'))->with('error', 'Eine USV mit der Konfiguration wurde bereits angelegt!');
      }

      $ups->save();
      return redirect(route('system.ups.create'))->with('success', 'USV erfolgreich angelegt!');
    }
    catch(Exception $e)
    {
      error_log($e);
      return redirect(route('system.ups.create'))->with('error', 'Ein unbekannter Fehler ist aufgetreten! Sollte dies öfter passieren, kontaktieren Sie bitte einen Administrator!');
    }
  }
}
