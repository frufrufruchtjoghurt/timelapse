<?php

namespace App\Http\Controllers;

use App\Fixture;
use App\Repair;
use App\System;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FixtureController extends Controller
{
  public function index()
  {

  }

    /**
   * Show page for fixture creation
   *
   *  @return \Illuminate\Contracts\Support\Renderable
   */
  public function create()
  {
    return view('system.fixture.create');
  }

  public function list()
  {
    return view('system.fixture.list', ['fixtures' => DB::table('fixtures', 'f')
    ->select('f.id', 'f.model', 'f.serial_nr', 'f.purchase_date', DB::raw('IFNULL(rc.count, 0) as count'))
    ->leftJoinSub(Repair::select('element_id', DB::raw('count(element_id) as count'))
      ->where('type', '=', 'f')->groupBy('element_id'), 'rc', function($join) {
        $join->on('f.id', '=', 'element_id');
    })->get()]);
  }

  public function edit($id)
  {
    try
    {
      $fixture = Fixture::findOrFail($id);

      return view('system.fixture.edit', ['fixture' => $fixture]);
    }
    catch(Exception $e)
    {
      error_log($e->getMessage());
      return redirect(route('fixture.list'))->with('error', 'Ein Fehler ist aufgetreten! Bitte versuchen Sie es erneut!');
    }
  }

  public function save($id)
  {
    try
    {
      $date = request('purchase_date_f');
      if ($date > date('Y-m-d') || $date < YEAR_MIN)
      {
        return redirect(route('fixture.edit', [$id]))->with('error', 'Es wurde kein gültiges Jahr angegeben!');
      }

      Fixture::where('id', $id)
        ->update([
          'serial_nr' => request('serial_nr_f'),
          'model' => request('type_f'),
          'purchase_date' => $date
        ]);

      return redirect(route('fixture.list'))->with('success', 'Gehäuse erfolgreich bearbeitet!');
    }
    catch (Exception $e)
    {
      error_log($e);
      return redirect(route('fixture.list'))
        ->with('error', 'Ein Fehler ist aufgetreten! Bitte versuchen Sie es erneut!');
    }
  }

  public function store()
  {
    try
    {
      $fixture = new Fixture();

      $fixture->serial_nr = request('serial_nr_f');
      $fixture->model = request('type_f');

      $date = request('purchase_date_f');
      if ($date > date('Y-m-d') || $date < YEAR_MIN)
      {
        return redirect(route('fixture.create'))->with('error', 'Es wurde kein gültiges Jahr angegeben!');
      }
      $fixture->purchase_date = $date;

      $first_match = Fixture::where([
        'serial_nr' => $fixture->serial_nr,
        'model' => $fixture->model,
        'purchase_date' => $fixture->purchase_date
      ]);

      if ($first_match->exists())
      {
        return redirect(route('fixture.create'))->with('error', 'Ein Gehäuse mit der Konfiguration wurde bereits angelegt!');
      }

      $fixture->save();
      return redirect(route('fixture.create'))->with('success', 'Gehäuse erfolgreich angelegt!');
    }
    catch(Exception $e)
    {
      error_log($e);
      return redirect(route('fixture.create'))->with('error', 'Ein unbekannter Fehler ist aufgetreten! Sollte dies öfter passieren, kontaktieren Sie bitte einen Administrator!');
    }
  }

  public function destroy($id)
  {
    if (request('delete') !== 'LÖSCHEN')
    {
      return redirect(route('fixture.list'))->with('error', 'Eingabe inkorrekt, Löschvorgang fehlgeschlagen!');
    }
    try
    {
      $fixture = Fixture::findOrFail($id);

      if (System::where('fixture_id', '=', $id)->exists())
      {
        return redirect(route('fixture.list'))->with('error', 'Das Gehäuse ist einem System zugewiesen, Löschvorgang fehlgeschlagen!');
      }

      $fixture->delete();

      return redirect(route('fixture.list'))->with('success', 'Gehäuse erfolgreich entfernt!');
    }
    catch (Exception $e)
    {
      error_log($e);
      return redirect(route('fixture.list'))->with('error', 'Ein Fehler ist aufgetreten! Bitte versuchen Sie es erneut!');
    }
  }
}
