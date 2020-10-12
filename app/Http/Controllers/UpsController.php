<?php

namespace App\Http\Controllers;

use App\Repair;
use App\System;
use App\Ups;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

  public function list()
  {
    return view('system.ups.list', ['ups' => DB::table('ups', 'u')
    ->select('u.id', 'u.model', 'u.serial_nr', 'u.purchase_date', DB::raw('IFNULL(rc.count, 0) as count'),
    'u.storage')
    ->leftJoinSub(Repair::select('element_id', DB::raw('count(element_id) as count'))
      ->where('type', '=', 'u')->groupBy('element_id'), 'rc', function($join) {
        $join->on('u.id', '=', 'element_id');
    })->get()]);
  }

  public function edit($id)
  {
    try
    {
      $ups = Ups::findOrFail($id);

      return view('system.ups.edit', ['ups' => $ups]);
    }
    catch(Exception $e)
    {
      error_log($e->getMessage());
      return redirect(route('ups.list'))->with('error', 'Ein Fehler ist aufgetreten! Bitte versuchen Sie es erneut!');
    }
  }

  public function save($id)
  {
    try
    {
      $date = request('purchase_date_u');
      if ($date > date('Y-m-d') || $date < YEAR_MIN)
      {
        return redirect(route('ups.edit', [$id]))->with('error', 'Es wurde kein gültiges Jahr angegeben!');
      }

      Ups::where('id', $id)
        ->update([
          'serial_nr' => request('serial_nr_u'),
          'model' => request('type_u'),
          'purchase_date' => $date
        ]);

      return redirect(route('ups.list'))->with('success', 'USV erfolgreich bearbeitet!');
    }
    catch (Exception $e)
    {
      error_log($e);
      return redirect(route('ups.list'))
        ->with('error', 'Ein Fehler ist aufgetreten! Bitte versuchen Sie es erneut!');
    }
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
        return redirect(route('ups.create'))->with('error', 'Es wurde kein gültiges Jahr angegeben!');
      }
      $ups->purchase_date = $date;

      $first_match = Ups::where([
        'serial_nr' => $ups->serial_nr,
        'model' => $ups->model,
        'purchase_date' => $ups->purchase_date
      ]);

      if ($first_match->exists())
      {
        return redirect(route('ups.create'))->with('error', 'Eine USV mit der Konfiguration wurde bereits angelegt!');
      }

      $ups->save();
      return redirect(route('ups.create'))->with('success', 'USV erfolgreich angelegt!');
    }
    catch(Exception $e)
    {
      error_log($e);
      return redirect(route('ups.create'))->with('error', 'Ein unbekannter Fehler ist aufgetreten! Sollte dies öfter passieren, kontaktieren Sie bitte einen Administrator!');
    }
  }

  public function destroy($id)
  {
    if (request('delete') !== 'LÖSCHEN')
    {
      return redirect(route('ups.list'))->with('error', 'Eingabe inkorrekt, Löschvorgang fehlgeschlagen!');
    }
    try
    {
      $ups = Ups::findOrFail($id);

      if (System::where('ups_id', '=', $id)->exists())
      {
        return redirect(route('ups.list'))->with('error', 'USV ist einem System zugewiesen, Löschvorgang fehlgeschlagen!');
      }

      $ups->delete();

      return redirect(route('ups.list'))->with('success', 'USV erfolgreich entfernt!');
    }
    catch (Exception $e)
    {
      error_log($e);
      return redirect(route('ups.list'))->with('error', 'Ein Fehler ist aufgetreten! Bitte versuchen Sie es erneut!');
    }
  }
}
