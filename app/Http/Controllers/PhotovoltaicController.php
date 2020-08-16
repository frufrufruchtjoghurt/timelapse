<?php

namespace App\Http\Controllers;

use App\Photovoltaic;
use Exception;
use Illuminate\Http\Request;

class PhotovoltaicController extends Controller
{
  public function index()
  {

  }

    /**
   * Show page for photovoltaic creation
   *
   *  @return \Illuminate\Contracts\Support\Renderable
   */
  public function create()
  {
    return view('system.photovoltaic.create');
  }

  public function store()
  {
    try
    {
      $photovoltaic = new Photovoltaic();

      $photovoltaic->serial_nr = request('serial_nr_p');
      $photovoltaic->model = request('type_p');

      $date = request('purchase_date_p');
      if ($date > date('Y-m-d') || $date < YEAR_MIN)
      {
        return redirect(route('router.create'))->with('error', 'Es wurde kein gültiges Jahr angegeben!');
      }
      $photovoltaic->purchase_date = $date;

      $first_match = Photovoltaic::where([
        'serial_nr' => $photovoltaic->serial_nr,
        'model' => $photovoltaic->model,
        'purchase_date' => $photovoltaic->purchase_date
      ]);

      if ($first_match->exists())
      {
        return redirect(route('system.photovoltaic.create'))->with('error', 'Eine Photovoltaikanlage mit der Konfiguration wurde bereits angelegt!');
      }

      $photovoltaic->save();
      return redirect(route('system.photovoltaic.create'))->with('success', 'Photovoltaikanlage erfolgreich angelegt!');
    }
    catch(Exception $e)
    {
      error_log($e);
      return redirect(route('system.photovoltaic.create'))->with('error', 'Ein unbekannter Fehler ist aufgetreten! Sollte dies öfter passieren, kontaktieren Sie bitte einen Administrator!');
    }
  }
}
