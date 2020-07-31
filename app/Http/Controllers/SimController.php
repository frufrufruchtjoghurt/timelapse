<?php

namespace App\Http\Controllers;

use App\Sim;
use Exception;
use Illuminate\Http\Request;

class SimController extends Controller
{
  public function index()
  {

  }

    /**
   * Show page for SIM creation
   *
   *  @return \Illuminate\Contracts\Support\Renderable
   */
  public function create()
  {
    return view('system.sim.create');
  }

  public function store()
  {
    try
    {
      $sim = new Sim();

      $sim->serial_nr = request('serial_nr_s');
      $sim->model = request('type_s');

      setlocale(LC_TIME, ['de_at', 'de_de', 'de']);
      $year = request('build_year_s');
      if ($year > strftime('%Y') || $year < YEAR_MIN)
      {
        return redirect(route('system.sim.create'))->with('error', 'Es wurde kein gültiges Jahr angegeben!');
      }
      $sim->build_year = $year;

      $first_match = Sim::where([
        'serial_nr' => $sim->serial_nr,
        'model' => $sim->model,
        'build_year' => $sim->build_year
      ]);

      if ($first_match->exists())
      {
        return redirect(route('system.sim.create'))->with('error', 'Eine Sim-Karte mit der Konfiguration wurde bereits angelegt!');
      }

      $sim->save();
      return redirect(route('system.sim.create'))->with('success', 'Sim-Karte erfolgreich angelegt!');
    }
    catch(Exception $e)
    {
      error_log($e);
      return redirect(route('system.sim.create'))->with('error', 'Ein unbekannter Fehler ist aufgetreten! Sollte dies öfter passieren, kontaktieren Sie bitte einen Administrator!');
    }
  }
}
