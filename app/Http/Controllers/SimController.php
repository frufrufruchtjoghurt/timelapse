<?php

namespace App\Http\Controllers;

use App\Repair;
use App\SimCard;
use App\System;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

  public function list()
  {
    return view('system.sim.list', ['sim_cards' => DB::table('sim_cards', 's')
    ->select('s.id', 's.contract', 's.telephone_nr', 's.purchase_date', DB::raw('IFNULL(rc.count, 0) as count'))
    ->leftJoinSub(Repair::select('element_id', DB::raw('count(element_id) as count'))
      ->where('type', '=', 's')->groupBy('element_id'), 'rc', function($join) {
        $join->on('s.id', '=', 'element_id');
    })->get()]);
  }

  public function edit($id)
  {
    try
    {
      $sim_card = SimCard::findOrFail($id);

      return view('system.sim.edit', ['sim_card' => $sim_card]);
    }
    catch(Exception $e)
    {
      error_log($e->getMessage());
      return redirect(route('sim.list'))->with('error', 'Ein Fehler ist aufgetreten! Bitte versuchen Sie es erneut!');
    }
  }

  public function save($id)
  {
    try
    {
      $date = request('purchase_date_s');
      if ($date > date('Y-m-d') || $date < YEAR_MIN)
      {
        return redirect(route('sim.edit', [$id]))->with('error', 'Es wurde kein gültiges Jahr angegeben!');
      }

      SimCard::where('id', $id)
        ->update([
          'telephone_nr' => request('telephone_nr_s'),
          'contract' => request('contract_s'),
          'purchase_date' => $date
        ]);

      return redirect(route('sim.list'))->with('success', 'Sim-Karte erfolgreich bearbeitet!');
    }
    catch (Exception $e)
    {
      error_log($e);
      return redirect(route('sim.list'))
        ->with('error', 'Ein Fehler ist aufgetreten! Bitte versuchen Sie es erneut!');
    }
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
        return redirect(route('sim.create'))->with('error', 'Es wurde kein gültiges Jahr angegeben!');
      }
      $sim->purchase_date = $date;

      $first_match = SimCard::where([
        'telephone_nr' => $sim->telephone_nr,
        'contract' => $sim->contract,
        'purchase_date' => $sim->purchase_date
      ]);

      if ($first_match->exists())
      {
        return redirect(route('sim.create'))->with('error', 'Eine Sim-Karte mit dieser Konfiguration wurde bereits angelegt!');
      }

      $sim->save();
      return redirect(route('sim.create'))->with('success', 'Sim-Karte erfolgreich angelegt!');
    }
    catch(Exception $e)
    {
      error_log($e);
      return redirect(route('sim.create'))->with('error', 'Ein unbekannter Fehler ist aufgetreten! Sollte dies öfter passieren, kontaktieren Sie bitte einen Administrator!');
    }
  }

  public function destroy($id)
  {
    if (request('delete') !== 'LÖSCHEN')
    {
      return redirect(route('sim.list'))->with('error', 'Eingabe inkorrekt, Löschvorgang fehlgeschlagen!');
    }
    try
    {
      $sim_card = SimCard::findOrFail($id);

      if (System::where('sim_id', '=', $id)->exists())
      {
        return redirect(route('sim.list'))->with('error', 'Sim-Karte ist einem System zugewiesen, Löschvorgang fehlgeschlagen!');
      }

      $sim_card->delete();

      return redirect(route('sim.list'))->with('success', 'Sim-Karte erfolgreich entfernt!');
    }
    catch (Exception $e)
    {
      error_log($e);
      return redirect(route('sim.list'))->with('error', 'Ein Fehler ist aufgetreten! Bitte versuchen Sie es erneut!');
    }
  }
}
