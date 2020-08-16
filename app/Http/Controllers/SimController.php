<?php

namespace App\Http\Controllers;

use App\SimCard;
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
      $sim = new SimCard();

      $sim->telephone_nr = request('telephone_nr_s');
      $sim->contract = request('contract_s');

      $date = request('purchase_date_s');
      if ($date > date('Y-m-d') || $date < YEAR_MIN)
      {
        return redirect(route('router.create'))->with('error', 'Es wurde kein gültiges Jahr angegeben!');
      }
      $sim->purchase_date = $date;

      $first_match = SimCard::where([
        'telephone_nr' => $sim->telephone_nr,
        'contract' => $sim->contract,
        'purchase_date' => $sim->purchase_date
      ]);

      if ($first_match->exists())
      {
        return redirect(route('system.sim.create'))->with('error', 'Eine Sim-Karte mit dieser Konfiguration wurde bereits angelegt!');
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
