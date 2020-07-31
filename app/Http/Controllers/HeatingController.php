<?php

namespace App\Http\Controllers;

use App\Heating;
use Exception;
use Illuminate\Http\Request;

class HeatingController extends Controller
{
  public function index()
  {

  }

    /**
   * Show page for heater creation
   *
   *  @return \Illuminate\Contracts\Support\Renderable
   */
  public function create()
  {
    return view('system.heating.create');
  }

  public function store()
  {
    try
    {
      $heating = new Heating();

      $heating->serial_nr = request('serial_nr_h');
      $heating->model = request('type_h');

      setlocale(LC_TIME, ['de_at', 'de_de', 'de']);
      $year = request('build_year_h');
      if ($year > strftime('%Y') || $year < YEAR_MIN)
      {
        return redirect(route('system.heating.create'))->with('error', 'Es wurde kein gültiges Jahr angegeben!');
      }
      $heating->build_year = $year;

      $first_match = Heating::where([
        'serial_nr' => $heating->serial_nr,
        'model' => $heating->model,
        'build_year' => $heating->build_year
      ]);

      if ($first_match->exists())
      {
        return redirect(route('system.heating.create'))->with('error', 'Eine Heizung mit der Konfiguration wurde bereits angelegt!');
      }

      $heating->save();
      return redirect(route('system.heating.create'))->with('success', 'Heizung erfolgreich angelegt!');
    }
    catch(Exception $e)
    {
      error_log($e);
      return redirect(route('system.heating.create'))->with('error', 'Ein unbekannter Fehler ist aufgetreten! Sollte dies öfter passieren, kontaktieren Sie bitte einen Administrator!');
    }
  }
}
