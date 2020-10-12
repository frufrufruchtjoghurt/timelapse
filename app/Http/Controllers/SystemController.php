<?php

namespace App\Http\Controllers;

use App\Fixture;
use App\Heating;
use App\Photovoltaic;
use App\Repair;
use App\Router;
use App\SimCard;
use App\System;
use App\Ups;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SystemController extends Controller
{
  public function index()
  {

  }

    /**
   * Show links to creatable components of the system
   *
   *  @return \Illuminate\Contracts\Support\Renderable
   */
  public function create()
  {
    return view('system.create', [
      'fixtures' => Fixture::select('fixtures.id', 'serial_nr', 'model', 'repair_c.repair_count', 'purchase_date')
        ->leftJoin('systems', 'fixtures.id', '=', 'systems.fixture_id')
        ->leftJoinSub(Repair::select('element_id', DB::raw('count(element_id) as repair_count'))
            ->where('type', '=', 'f')->groupBy('element_id'), 'repair_c', function($join) {
            $join->on('fixtures.id', '=', 'element_id');
        })
        ->where('broken', '=', false)
        ->whereNull('systems.fixture_id')
        ->get(),
      'routers' => Router::select('routers.id', 'serial_nr', 'model', 'repair_c.repair_count', 'purchase_date')
        ->leftJoin('systems', 'routers.id', '=', 'systems.router_id')
        ->leftJoinSub(Repair::select('element_id', DB::raw('count(element_id) as repair_count'))
            ->where('type', '=', 'r')->groupBy('element_id'), 'repair_c', function($join) {
            $join->on('routers.id', '=', 'element_id');
        })
        ->where('broken', '=', false)
        ->whereNull('systems.router_id')
        ->get(),
      'sims' => SimCard::select('sim_cards.id', 'telephone_nr', 'contract', 'repair_c.repair_count', 'purchase_date')
        ->leftJoin('systems', 'sim_cards.id', '=', 'systems.sim_id')
        ->leftJoinSub(Repair::select('element_id', DB::raw('count(element_id) as repair_count'))
            ->where('type', '=', 's')->groupBy('element_id'), 'repair_c', function($join) {
            $join->on('sim_cards.id', '=', 'element_id');
        })
        ->where('broken', '=', false)
        ->whereNull('systems.sim_id')
        ->get(),
      'ups' => Ups::select('ups.id', 'serial_nr', 'model', 'repair_c.repair_count', 'purchase_date')
        ->leftJoin('systems', 'ups.id', '=', 'systems.ups_id')
        ->leftJoinSub(Repair::select('element_id', DB::raw('count(element_id) as repair_count'))
            ->where('type', '=', 'u')->groupBy('element_id'), 'repair_c', function($join) {
            $join->on('ups.id', '=', 'element_id');
        })
        ->where('broken', '=', false)
        ->whereNull('systems.ups_id')
        ->get(),
      'heatings' => Heating::select('heatings.id', 'serial_nr', 'model', 'repair_c.repair_count', 'purchase_date')
        ->leftJoin('systems', 'heatings.id', '=', 'systems.heating_id')
        ->leftJoinSub(Repair::select('element_id', DB::raw('count(element_id) as repair_count'))
            ->where('type', '=', 'h')->groupBy('element_id'), 'repair_c', function($join) {
            $join->on('heatings.id', '=', 'element_id');
        })
        ->where('broken', '=', false)
        ->whereNull('systems.heating_id')
        ->get(),
      'photovoltaics' => Photovoltaic::select('photovoltaics.id', 'serial_nr', 'model', 'repair_c.repair_count', 'purchase_date')
        ->leftJoin('systems', 'photovoltaics.id', '=', 'systems.photovoltaic_id')
        ->leftJoinSub(Repair::select('element_id', DB::raw('count(element_id) as repair_count'))
            ->where('type', '=', 'p')->groupBy('element_id'), 'repair_c', function($join) {
            $join->on('photovoltaics.id', '=', 'element_id');
        })
        ->where('broken', '=', false)
        ->whereNull('systems.photovoltaic_id')
        ->get()
      ]);
  }

  public function list()
  {
    $systems = DB::table('systems as y')
      ->select('y.id', 'y.name', 'f.id as id_f', 'f.model as model_f', DB::raw('IFNULL(rc_f.count, 0) as count_f'),
        'r.id as id_r', 'r.model as model_r', DB::raw('IFNULL(rc_r.count, 0) as count_r'), 's.id as id_s',
        's.contract as model_s', DB::raw('IFNULL(rc_s.count, 0) as count_s'), 'u.id as id_u', 'u.model as model_u',
        DB::raw('IFNULL(rc_u.count, 0) as count_u'), 'h.id as id_h', 'h.model as model_h',
        DB::raw('IFNULL(rc_h.count, 0) as count_h'), 'p.id as id_p', 'p.model as model_p',
        DB::raw('IFNULL(rc_p.count, 0) as count_p'), 'r.storage as storage')
      ->join('fixtures as f', 'f.id', '=', 'y.fixture_id')
      ->leftJoinSub(Repair::select('element_id', DB::raw('count(element_id) as count'))
        ->where('type', '=', 'f')->groupBy('element_id'), 'rc_f', function($join) {
          $join->on('f.id', '=', 'element_id');
      })
      ->join('routers as r', 'r.id', '=', 'y.router_id')
      ->leftJoinSub(Repair::select(DB::raw('count(element_id) as count'))
        ->where('type', '=', 'r')->groupBy('element_id'), 'rc_r', function($join) {
          $join->on('r.id', '=', 'element_id');
      })
      ->join('sim_cards as s', 's.id', '=', 'y.sim_id')
      ->leftJoinSub(Repair::select(DB::raw('count(element_id) as count'))
        ->where('type', '=', 's')->groupBy('element_id'), 'rc_s', function($join) {
          $join->on('s.id', '=', 'element_id');
      })
      ->join('ups as u', 'u.id', '=', 'y.ups_id')
      ->leftJoinSub(Repair::select(DB::raw('count(element_id) as count'))
        ->where('type', '=', 'u')->groupBy('element_id'), 'rc_u', function($join) {
          $join->on('u.id', '=', 'element_id');
      })
      ->leftJoin('heatings as h', 'h.id', '=', 'y.heating_id')
      ->leftJoinSub(Repair::select(DB::raw('count(element_id) as count'))
        ->where('type', '=', 'h')->groupBy('element_id'), 'rc_h', function($join) {
          $join->on('h.id', '=', 'element_id');
      })
      ->leftJoin('photovoltaics as p', 'p.id', '=', 'y.photovoltaic_id')
      ->leftJoinSub(Repair::select(DB::raw('count(element_id) as count'))
        ->where('type', '=', 'p')->groupBy('element_id'), 'rc_p', function($join) {
          $join->on('p.id', '=', 'element_id');
      })
      ->get();

    return view('system.list', ['systems' => $systems]);
  }

  public function show($id_f, $id_r, $id_u)
  {
    try
      {
        $system = System::where([
            'fixture_id' => $id_f,
            'router_id' => $id_r,
            'ups_id' => $id_u
          ])
          ->firstOrFail();

        $fixture = Fixture::select('id', 'model', 'serial_nr', 'purchase_date', DB::raw('IFNULL(rc.count, 0) as count'),
          'storage')
          ->leftJoinSub(Repair::select('element_id', DB::raw('count(element_id) as count'))
          ->where('type', '=', 'f')->groupBy('element_id'), 'rc', function($join) {
              $join->on('id', '=', 'element_id');
            })
          ->where('id', '=', $id_f)
          ->firstOrFail();

        $router = Router::select('id', 'model', 'serial_nr', 'purchase_date', DB::raw('IFNULL(rc.count, 0) as count'))
          ->leftJoinSub(Repair::select('element_id', DB::raw('count(element_id) as count'))
          ->where('type', '=', 'r')->groupBy('element_id'), 'rc', function($join) {
              $join->on('id', '=', 'element_id');
            })
          ->where('id', '=', $id_r)
          ->firstOrFail();

        $sim_card = SimCard::select('id', 'contract', 'telephone_nr', 'purchase_date', DB::raw('IFNULL(rc.count, 0) as count'))
          ->leftJoinSub(Repair::select('element_id', DB::raw('count(element_id) as count'))
          ->where('type', '=', 's')->groupBy('element_id'), 'rc', function($join) {
              $join->on('id', '=', 'element_id');
            })
          ->where('id', '=', $system->sim_id)
          ->firstOrFail();

        $ups = Ups::select('id', 'model', 'serial_nr', 'purchase_date', DB::raw('IFNULL(rc.count, 0) as count'))
          ->leftJoinSub(Repair::select('element_id', DB::raw('count(element_id) as count'))
          ->where('type', '=', 'u')->groupBy('element_id'), 'rc', function($join) {
              $join->on('id', '=', 'element_id');
            })
          ->where('id', '=', $id_u)
          ->firstOrFail();

        $heating = null;
        $photovoltaic = null;

        if ($system->heating_id)
        {
          $heating = Heating::select('id', 'model', 'serial_nr', 'purchase_date', DB::raw('IFNULL(rc.count, 0) as count'))
            ->leftJoinSub(Repair::select('element_id', DB::raw('count(element_id) as count'))
            ->where('type', '=', 'h')->groupBy('element_id'), 'rc', function($join) {
                $join->on('id', '=', 'element_id');
              })
            ->where('id', '=', $system->heating_id)
            ->firstOrFail();
        }

        if ($system->photovoltaic_id)
        {
          $photovoltaic = Photovoltaic::select('id', 'model', 'serial_nr', 'purchase_date', DB::raw('IFNULL(rc.count, 0) as count'))
            ->leftJoinSub(Repair::select('element_id', DB::raw('count(element_id) as count'))
            ->where('type', '=', 'p')->groupBy('element_id'), 'rc', function($join) {
                $join->on('id', '=', 'element_id');
              })
            ->where('id', '=', $system->photovoltaic_id)
            ->firstOrFail();
        }

      }
      catch(Exception $e)
      {
        \error_log($e->getMessage());
        return \redirect(route('system.list'))->with('error', 'Eine ID konnte nicht gefunden werden!');
      }

      return view('system.show', ['fixture' => $fixture, 'router' => $router, 'sim_card' => $sim_card, 'ups' => $ups, 'heating' => $heating, 'photovoltaic' => $photovoltaic]);
  }

  public function edit($id_f, $id_r, $id_u)
  {
    try
    {
      $old_system = System::where([
          'fixture_id' => $id_f,
          'router_id' => $id_r,
          'ups_id' => $id_u
        ])
        ->firstOrFail();

      $old_fixture = Fixture::select('id', 'model', 'serial_nr', 'purchase_date', DB::raw('IFNULL(rc.count, 0) as count'))
        ->leftJoinSub(Repair::select('element_id', DB::raw('count(element_id) as count'))
        ->where('type', '=', 'f')->groupBy('element_id'), 'rc', function($join) {
            $join->on('id', '=', 'element_id');
          })
        ->where('id', '=', $id_f)
        ->firstOrFail();

      $old_router = Router::select('id', 'model', 'serial_nr', 'purchase_date', DB::raw('IFNULL(rc.count, 0) as count'))
        ->leftJoinSub(Repair::select('element_id', DB::raw('count(element_id) as count'))
        ->where('type', '=', 'r')->groupBy('element_id'), 'rc', function($join) {
            $join->on('id', '=', 'element_id');
          })
        ->where('id', '=', $id_r)
        ->firstOrFail();

      $old_sim_card = SimCard::select('id', 'contract', 'telephone_nr', 'purchase_date', DB::raw('IFNULL(rc.count, 0) as count'))
        ->leftJoinSub(Repair::select('element_id', DB::raw('count(element_id) as count'))
        ->where('type', '=', 's')->groupBy('element_id'), 'rc', function($join) {
            $join->on('id', '=', 'element_id');
          })
        ->where('id', '=', $old_system->sim_id)
        ->firstOrFail();

      $old_ups = Ups::select('id', 'model', 'serial_nr', 'purchase_date', DB::raw('IFNULL(rc.count, 0) as count'))
        ->leftJoinSub(Repair::select('element_id', DB::raw('count(element_id) as count'))
        ->where('type', '=', 'u')->groupBy('element_id'), 'rc', function($join) {
            $join->on('id', '=', 'element_id');
          })
        ->where('id', '=', $id_u)
        ->firstOrFail();

      $old_heating = null;
      $old_photovoltaic = null;

      if ($old_system->heating_id)
      {
        $old_heating = Heating::select('id', 'model', 'serial_nr', 'purchase_date', DB::raw('IFNULL(rc.count, 0) as count'))
          ->leftJoinSub(Repair::select('element_id', DB::raw('count(element_id) as count'))
          ->where('type', '=', 'h')->groupBy('element_id'), 'rc', function($join) {
              $join->on('id', '=', 'element_id');
            })
          ->where('id', '=', $old_system->heating_id)
          ->firstOrFail();
      }

      if ($old_system->photovoltaic_id)
      {
        $old_photovoltaic = Photovoltaic::select('id', 'model', 'serial_nr', 'purchase_date', DB::raw('IFNULL(rc.count, 0) as count'))
          ->leftJoinSub(Repair::select('element_id', DB::raw('count(element_id) as count'))
          ->where('type', '=', 'p')->groupBy('element_id'), 'rc', function($join) {
              $join->on('id', '=', 'element_id');
            })
          ->where('id', '=', $old_system->photovoltaic_id)
          ->firstOrFail();
      }

      return view('system.edit', [
        'old_fixture' => $old_fixture,
        'old_router' => $old_router,
        'old_sim_card' => $old_sim_card,
        'old_ups' => $old_ups,
        'old_heating' => $old_heating,
        'old_photovoltaic' => $old_photovoltaic,
        'fixtures' => Fixture::select('fixtures.id', 'serial_nr', 'model', 'repair_c.repair_count', 'purchase_date')
        ->leftJoin('systems', 'fixtures.id', '=', 'systems.fixture_id')
        ->leftJoinSub(Repair::select('element_id', DB::raw('count(element_id) as repair_count'))
            ->where('type', '=', 'f')->groupBy('element_id'), 'repair_c', function($join) {
            $join->on('fixtures.id', '=', 'element_id');
        })
        ->where('broken', '=', false)
        ->whereNull('systems.fixture_id')
        ->get(),
      'routers' => Router::select('routers.id', 'serial_nr', 'model', 'repair_c.repair_count', 'purchase_date')
        ->leftJoin('systems', 'routers.id', '=', 'systems.router_id')
        ->leftJoinSub(Repair::select('element_id', DB::raw('count(element_id) as repair_count'))
            ->where('type', '=', 'r')->groupBy('element_id'), 'repair_c', function($join) {
            $join->on('routers.id', '=', 'element_id');
        })
        ->where('broken', '=', false)
        ->whereNull('systems.router_id')
        ->get(),
      'sims' => SimCard::select('sim_cards.id', 'telephone_nr', 'contract', 'repair_c.repair_count', 'purchase_date')
        ->leftJoin('systems', 'sim_cards.id', '=', 'systems.sim_id')
        ->leftJoinSub(Repair::select('element_id', DB::raw('count(element_id) as repair_count'))
            ->where('type', '=', 's')->groupBy('element_id'), 'repair_c', function($join) {
            $join->on('sim_cards.id', '=', 'element_id');
        })
        ->where('broken', '=', false)
        ->whereNull('systems.sim_id')
        ->get(),
      'ups' => Ups::select('ups.id', 'serial_nr', 'model', 'repair_c.repair_count', 'purchase_date')
        ->leftJoin('systems', 'ups.id', '=', 'systems.ups_id')
        ->leftJoinSub(Repair::select('element_id', DB::raw('count(element_id) as repair_count'))
            ->where('type', '=', 'u')->groupBy('element_id'), 'repair_c', function($join) {
            $join->on('ups.id', '=', 'element_id');
        })
        ->where('broken', '=', false)
        ->whereNull('systems.ups_id')
        ->get(),
      'heatings' => Heating::select('heatings.id', 'serial_nr', 'model', 'repair_c.repair_count', 'purchase_date')
        ->leftJoin('systems', 'heatings.id', '=', 'systems.heating_id')
        ->leftJoinSub(Repair::select('element_id', DB::raw('count(element_id) as repair_count'))
            ->where('type', '=', 'h')->groupBy('element_id'), 'repair_c', function($join) {
            $join->on('heatings.id', '=', 'element_id');
        })
        ->where('broken', '=', false)
        ->whereNull('systems.heating_id')
        ->get(),
      'photovoltaics' => Photovoltaic::select('photovoltaics.id', 'serial_nr', 'model', 'repair_c.repair_count', 'purchase_date')
        ->leftJoin('systems', 'photovoltaics.id', '=', 'systems.photovoltaic_id')
        ->leftJoinSub(Repair::select('element_id', DB::raw('count(element_id) as repair_count'))
            ->where('type', '=', 'p')->groupBy('element_id'), 'repair_c', function($join) {
            $join->on('photovoltaics.id', '=', 'element_id');
        })
        ->where('broken', '=', false)
        ->whereNull('systems.photovoltaic_id')
        ->get()
      ]);
    }
    catch (Exception $e)
    {
      error_log($e);
      return redirect(route('system.list'))->with('Ein Fehler ist aufgetreten! Bitte versuchen Sie es erneut!');
    }
  }

  public function save($id_f, $id_r, $id_u)
  {
    try
    {
      $heating = request('heating');
      $photovoltaic = request('photovoltaic');

      if (!$heating)
      {
        $heating = 'NULL';
      }

      if (!$photovoltaic)
      {
        $photovoltaic = 'NULL';
      }

      System::where([
          'fixture_id' => $id_f,
          'router_id' => $id_r,
          'ups_id' => $id_u
        ])
        ->update([
          'fixture_id' => request('fixture'),
          'router_id' => request('router'),
          'sim_id' => request('sim'),
          'ups_id' => request('ups'),
          'heating_id' => $heating,
          'photovoltaic_id' => $photovoltaic
        ]);

      return redirect(route('system.list'))->with('success', 'System erfolgreich bearbeitet!');
    }
    catch (Exception $e)
    {
      error_log($e);
      return redirect(route('system.show', ['id_f' => $id_f, 'id_r' => $id_r, 'id_u' => $id_u]))
        ->with('error', 'Ein Fehler ist aufgetreten! Bitte versuchen Sie es erneut!');
    }
  }

  public function store()
  {
    try
    {
      $system = new System();

      $system->name = request('name');
      $system->fixture_id = request('fixture');
      $system->router_id = request('router');
      $system->sim_id = request('sim');
      $system->ups_id = request('ups');
      $p_id = request('photovoltaic');
      $h_id = request('heating');
      $system->photovoltaic_id = $p_id ? $p_id : NULL;
      $system->heating_id = $h_id ? $h_id : NULL;

      if (System::where(['fixture_id' => $system->fixture_id])->exists()
        || System::where(['router_id' => $system->router_id])->exists()
        || System::where(['sim_id' => $system->sim_id])->exists()
        || System::where(['ups_id' => $system->ups_id])->exists()
        || System::where(['photovoltaic_id' => $system->photovoltaic_id])->exists()
        || System::where(['heating_id' => $system->heating_id])->exists())
      {
        return redirect(route('system.create'))
          ->with('error', 'Eine oder mehrere Komponenten sind bereits in Verwendung!');
      }

      $system->save();
      return redirect(route('system.create'))->with('success', 'System erfolgreich angelegt!');
    }
    catch (Exception $e)
    {
      error_log($e);
      return redirect(route('system.create'))
        ->with('error', 'Ein unbekannter Fehler ist aufgetreten! Sollte dies öfter passieren, kontaktieren Sie bitte einen Administrator!');
    }
  }

  public function destroy($id_f, $id_r, $id_u)
  {
    if (request('delete') !== 'LÖSCHEN')
    {
      return redirect(route('system.show', ['id_f' => $id_f, 'id_r' => $id_r, 'id_u' => $id_u]))
        ->with('error', 'Eingabe inkorrekt! Löschvorgang fehlgeschlagen!');
    }
    try
    {
      $system = System::where([
          'fixture_id' => $id_f,
          'router_id' => $id_r,
          'ups_id' => $id_u
        ])
        ->findOrFail();

      $system->delete();

      return redirect(route('system.list'))->with('success', 'System wurde erfolgreich entfernt');
    }
    catch (Exception $e)
    {
      error_log($e);

      return redirect(route('system.list'))->with('error', 'Ein Fehler ist aufgetreten! Bitte versuchen Sie es erneut!');
    }

  }
}
