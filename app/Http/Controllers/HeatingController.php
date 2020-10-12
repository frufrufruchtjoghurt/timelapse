<?php

namespace App\Http\Controllers;

use App\Heating;
use App\Repair;
use App\System;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

  public function list()
  {
    return view('system.heating.list', ['heatings' => DB::table('heatings', 'h')
    ->select('h.id', 'h.model', 'h.serial_nr', 'h.purchase_date', DB::raw('IFNULL(rc.count, 0) as count'),
      'h.storage')
    ->leftJoinSub(Repair::select('element_id', DB::raw('count(element_id) as count'))
      ->where('type', '=', 'h')->groupBy('element_id'), 'rc', function($join) {
        $join->on('h.id', '=', 'element_id');
    })->get()]);
  }

  public function edit($id)
  {
    try
    {
      $heating = Heating::findOrFail($id);

      return view('system.heating.edit', ['heating' => $heating]);
    }
    catch(Exception $e)
    {
      error_log($e->getMessage());
      return redirect(route('heating.list'))->with('error', 'Ein Fehler ist aufgetreten! Bitte versuchen Sie es erneut!');
    }
  }

  public function save($id)
  {
    try
    {
      $date = request('purchase_date_h');
      if ($date > date('Y-m-d') || $date < YEAR_MIN)
      {
        return redirect(route('heating.edit', [$id]))->with('error', 'Es wurde kein gültiges Jahr angegeben!');
      }

      Heating::where('id', $id)
        ->update([
          'serial_nr' => request('serial_nr_h'),
          'model' => request('type_h'),
          'purchase_date' => $date
        ]);

      return redirect(route('heating.list'))->with('success', 'Gehäuse erfolgreich bearbeitet!');
    }
    catch (Exception $e)
    {
      error_log($e);
      return redirect(route('heating.list'))
        ->with('error', 'Ein Fehler ist aufgetreten! Bitte versuchen Sie es erneut!');
    }
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
        return redirect(route('heating.create'))->with('error', 'Es wurde kein gültiges Jahr angegeben!');
      }
      $heating->purchase_date = $date;

      $first_match = Heating::where([
        'serial_nr' => $heating->serial_nr,
        'model' => $heating->model,
        'purchase_date' => $heating->purchase_date
      ]);

      if ($first_match->exists())
      {
        return redirect(route('heating.create'))->with('error', 'Eine Heizung mit der Konfiguration wurde bereits angelegt!');
      }

      $heating->save();
      return redirect(route('heating.create'))->with('success', 'Heizung erfolgreich angelegt!');
    }
    catch(Exception $e)
    {
      error_log($e);
      return redirect(route('heating.create'))->with('error', 'Ein unbekannter Fehler ist aufgetreten! Sollte dies öfter passieren, kontaktieren Sie bitte einen Administrator!');
    }
  }

  public function destroy($id)
  {
    if (request('delete') !== 'LÖSCHEN')
    {
      return redirect(route('heating.list'))->with('error', 'Eingabe inkorrekt, Löschvorgang fehlgeschlagen!');
    }
    try
    {
      $heating = Heating::findOrFail($id);

      if (System::where('heating_id', '=', $id)->exists())
      {
        return redirect(route('heating.list'))->with('error', 'Heizung ist einem System zugewiesen, Löschvorgang fehlgeschlagen!');
      }

      $heating->delete();

      return redirect(route('heating.list'))->with('success', 'Heizung erfolgreich entfernt!');
    }
    catch (Exception $e)
    {
      error_log($e);
      return redirect(route('heating.list'))->with('error', 'Ein Fehler ist aufgetreten! Bitte versuchen Sie es erneut!');
    }
  }
}
