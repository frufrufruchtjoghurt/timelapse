<?php

namespace App\Http\Controllers;

use App\Repair;
use App\Router;
use App\System;
use Exception;
use Illuminate\Support\Facades\DB;

class RouterController extends Controller
{
  public function index()
  {

  }

    /**
   * Show page for router creation
   *
   *  @return \Illuminate\Contracts\Support\Renderable
   */
  public function create()
  {
    return view('system.router.create');
  }

  public function list()
  {
    return view('system.router.list', ['routers' => DB::table('routers', 'r')
    ->select('r.id', 'r.model', 'r.serial_nr', 'r.purchase_date', DB::raw('IFNULL(rc.count, 0) as count'),
      'r.storage')
    ->leftJoinSub(Repair::select('element_id', DB::raw('count(element_id) as count'))
      ->where('type', '=', 'r')->groupBy('element_id'), 'rc', function($join) {
        $join->on('r.id', '=', 'element_id');
    })->get()]);
  }

  public function edit($id)
  {
    try
    {
      $router = Router::findOrFail($id);

      return view('system.router.edit', ['router' => $router]);
    }
    catch(Exception $e)
    {
      error_log($e->getMessage());
      return redirect(route('router.list'))->with('error', 'Ein Fehler ist aufgetreten! Bitte versuchen Sie es erneut!');
    }
  }

  public function save($id)
  {
    try
    {
      $date = request('purchase_date_r');
      if ($date > date('Y-m-d') || $date < YEAR_MIN)
      {
        return redirect(route('router.edit', [$id]))->with('error', 'Es wurde kein gültiges Jahr angegeben!');
      }

      Router::where('id', $id)
        ->update([
          'serial_nr' => request('serial_nr_r'),
          'model' => request('type_r'),
          'purchase_date' => $date
        ]);

      return redirect(route('router.list'))->with('success', 'Router erfolgreich bearbeitet!');
    }
    catch (Exception $e)
    {
      error_log($e);
      return redirect(route('router.list'))
        ->with('error', 'Ein Fehler ist aufgetreten! Bitte versuchen Sie es erneut!');
    }
  }

  public function store()
  {
    try
    {
      $router = new Router();

      $router->serial_nr = request('serial_nr_r');
      $router->model = request('type_r');

      $date = request('purchase_date_r');
      if ($date > date('Y-m-d') || $date < YEAR_MIN)
      {
        return redirect(route('router.create'))->with('error', 'Es wurde kein gültiges Jahr angegeben!');
      }
      $router->purchase_date = $date;

      $first_match = Router::where([
        'serial_nr' => $router->serial_nr,
        'model' => $router->model,
        'purchase_date' => $router->purchase_date
      ]);

      if ($first_match->exists())
      {
        return redirect(route('router.create'))->with('error', 'Ein Router mit der Konfiguration wurde bereits angelegt!');
      }

      $router->save();
      return redirect(route('router.create'))->with('success', 'Router erfolgreich angelegt!');
    }
    catch(Exception $e)
    {
      error_log($e);
      return redirect(route('router.create'))->with('error', 'Ein unbekannter Fehler ist aufgetreten! Sollte dies öfter passieren, kontaktieren Sie bitte einen Administrator!');
    }
  }

  public function destroy($id)
  {
    if (request('delete') !== 'LÖSCHEN')
    {
      return redirect(route('router.list'))->with('error', 'Eingabe inkorrekt, Löschvorgang fehlgeschlagen!');
    }
    try
    {
      $router = Router::findOrFail($id);

      if (System::where('router_id', '=', $id)->exists())
      {
        return redirect(route('router.list'))->with('error', 'Router ist einem System zugewiesen, Löschvorgang fehlgeschlagen!');
      }

      $router->delete();

      return redirect(route('router.list'))->with('success', 'Router erfolgreich entfernt!');
    }
    catch (Exception $e)
    {
      error_log($e);
      return redirect(route('router.list'))->with('error', 'Ein Fehler ist aufgetreten! Bitte versuchen Sie es erneut!');
    }
  }
}
