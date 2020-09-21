<?php

namespace App\Http\Controllers;

use App\Photovoltaic;
use App\Repair;
use App\System;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

  public function list()
  {
    return view('system.photovoltaic.list', ['photovoltaics' => DB::table('photovoltaics', 'p')
    ->select('p.id', 'p.model', 'p.serial_nr', 'p.purchase_date', DB::raw('IFNULL(rc.count, 0) as count'))
    ->leftJoinSub(Repair::select('element_id', DB::raw('count(element_id) as count'))
      ->where('type', '=', 'p')->groupBy('element_id'), 'rc', function($join) {
        $join->on('p.id', '=', 'element_id');
    })->get()]);
  }

  public function edit($id)
  {
    try
    {
      $photovoltaic = Photovoltaic::findOrFail($id);

      return view('system.photovoltaic.edit', ['photovoltaic' => $photovoltaic]);
    }
    catch(Exception $e)
    {
      error_log($e->getMessage());
      return redirect(route('photovoltaic.list'))->with('error', 'Ein Fehler ist aufgetreten! Bitte versuchen Sie es erneut!');
    }
  }

  public function save($id)
  {
    try
    {
      $date = request('purchase_date_p');
      if ($date > date('Y-m-d') || $date < YEAR_MIN)
      {
        return redirect(route('photovoltaic.edit', [$id]))->with('error', 'Es wurde kein gültiges Jahr angegeben!');
      }

      Photovoltaic::where('id', $id)
        ->update([
          'serial_nr' => request('serial_nr_p'),
          'model' => request('type_p'),
          'purchase_date' => $date
        ]);

      return redirect(route('photovoltaic.list'))->with('success', 'Photovoltaikanlage erfolgreich bearbeitet!');
    }
    catch (Exception $e)
    {
      error_log($e);
      return redirect(route('photovoltaic.list'))
        ->with('error', 'Ein Fehler ist aufgetreten! Bitte versuchen Sie es erneut!');
    }
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
        return redirect(route('photovoltaic.create'))->with('error', 'Es wurde kein gültiges Jahr angegeben!');
      }
      $photovoltaic->purchase_date = $date;

      $first_match = Photovoltaic::where([
        'serial_nr' => $photovoltaic->serial_nr,
        'model' => $photovoltaic->model,
        'purchase_date' => $photovoltaic->purchase_date
      ]);

      if ($first_match->exists())
      {
        return redirect(route('photovoltaic.create'))->with('error', 'Eine Photovoltaikanlage mit der Konfiguration wurde bereits angelegt!');
      }

      $photovoltaic->save();
      return redirect(route('photovoltaic.create'))->with('success', 'Photovoltaikanlage erfolgreich angelegt!');
    }
    catch(Exception $e)
    {
      error_log($e);
      return redirect(route('photovoltaic.create'))->with('error', 'Ein unbekannter Fehler ist aufgetreten! Sollte dies öfter passieren, kontaktieren Sie bitte einen Administrator!');
    }
  }

  public function destroy($id)
  {
    if (request('delete') !== 'LÖSCHEN')
    {
      return redirect(route('photovoltaic.list'))->with('error', 'Eingabe inkorrekt, Löschvorgang fehlgeschlagen!');
    }
    try
    {
      $photovoltaic = Photovoltaic::findOrFail($id);

      if (System::where('photovoltaic_id', '=', $id)->exists())
      {
        return redirect(route('photovoltaic.list'))->with('error', 'Photovoltaikanlage ist einem System zugewiesen, Löschvorgang fehlgeschlagen!');
      }

      $photovoltaic->delete();

      return redirect(route('photovoltaic.list'))->with('success', 'Photovoltaikanlage erfolgreich entfernt!');
    }
    catch (Exception $e)
    {
      error_log($e);
      return redirect(route('photovoltaic.list'))->with('error', 'Ein Fehler ist aufgetreten! Bitte versuchen Sie es erneut!');
    }
  }
}
