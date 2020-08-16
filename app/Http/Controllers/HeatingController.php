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

      $date = request('purchase_date_h');
      if ($date > date('Y-m-d') || $date < YEAR_MIN)
      {
        return redirect(route('router.create'))->with('error', 'Es wurde kein gültiges Jahr angegeben!');
      }
      $heating->purchase_date = $date;

      $first_match = Heating::where([
        'serial_nr' => $heating->serial_nr,
        'model' => $heating->model,
        'purchase_date' => $heating->purchase_date
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
