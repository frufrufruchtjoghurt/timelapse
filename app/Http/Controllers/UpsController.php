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

      $date = request('purchase_date_u');
      if ($date > date('Y-m-d') || $date < YEAR_MIN)
      {
        return redirect(route('router.create'))->with('error', 'Es wurde kein gültiges Jahr angegeben!');
      }
      $ups->purchase_date = $date;

      $first_match = Ups::where([
        'serial_nr' => $ups->serial_nr,
        'model' => $ups->model,
        'purchase_date' => $ups->purchase_date
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
