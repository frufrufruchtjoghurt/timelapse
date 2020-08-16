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
      'fixtures' => Fixture::select('id', 'serial_nr', 'model', 'repair_c.repair_count', 'purchase_date')
        ->leftJoin('systems', 'fixtures.id', '=', 'systems.router_id')
        ->joinSub(Repair::select('element_id', DB::raw('count(element_id) as repair_count'))
            ->where('type', '=', 'f')->groupBy('element_id'), 'repair_c', function($join) {
            $join->on('fixtures.id', '=', 'element_id');
        })
        ->where('broken', '=', false)
        ->get(),
      'routers' => Router::select('id', 'serial_nr', 'model', 'repair_c.repair_count', 'purchase_date')
        ->leftJoin('systems', 'routers.id', '=', 'systems.router_id')
        ->leftJoinSub(Repair::select('element_id', DB::raw('count(element_id) as repair_count'))
            ->where('type', '=', 'r')->groupBy('element_id'), 'repair_c', function($join) {
            $join->on('routers.id', '=', 'element_id');
        })
        ->where('broken', '=', false)
        ->get(),
      'sims' => SimCard::select('id', 'telephone_nr', 'contract', 'repair_c.repair_count', 'purchase_date')
        ->leftJoin('systems', 'sim_cards.id', '=', 'systems.router_id')
        ->joinSub(Repair::select('element_id', DB::raw('count(element_id) as repair_count'))
            ->where('type', '=', 's')->groupBy('element_id'), 'repair_c', function($join) {
            $join->on('sim_cards.id', '=', 'element_id');
        })
        ->where('broken', '=', false)
        ->get(),
      'ups' => Ups::select('id', 'serial_nr', 'model', 'repair_c.repair_count', 'purchase_date')
        ->leftJoin('systems', 'ups.id', '=', 'systems.router_id')
        ->joinSub(Repair::select('element_id', DB::raw('count(element_id) as repair_count'))
            ->where('type', '=', 'u')->groupBy('element_id'), 'repair_c', function($join) {
            $join->on('ups.id', '=', 'element_id');
        })
        ->where('broken', '=', false)
        ->get(),
      'heatings' => Heating::select('id', 'serial_nr', 'model', 'repair_c.repair_count', 'purchase_date')
          ->leftJoin('systems', 'heatings.id', '=', 'systems.router_id')
          ->joinSub(Repair::select('element_id', DB::raw('count(element_id) as repair_count'))
              ->where('type', '=', 'h')->groupBy('element_id'), 'repair_c', function($join) {
              $join->on('heatings.id', '=', 'element_id');
          })
          ->where('broken', '=', false)
          ->get(),
      'photovoltaics' => Photovoltaic::select('id', 'serial_nr', 'model', 'repair_c.repair_count', 'purchase_date')
        ->leftJoin('systems', 'photovoltaics.id', '=', 'systems.router_id')
        ->joinSub(Repair::select('element_id', DB::raw('count(element_id) as repair_count'))
            ->where('type', '=', 'p')->groupBy('element_id'), 'repair_c', function($join) {
            $join->on('photovoltaics.id', '=', 'element_id');
        })
        ->where('broken', '=', false)
        ->get()
      ]);
  }

  public function store()
  {
    try {
      $system = new System();

      $system->fixture_id = request('fixture');
      $system->router_id = request('router');
      $system->sim_id = request('sim');
      $system->ups_id = request('ups');
      $system->photovoltaic_id = request('photovoltaic');
      $system->heating_id = request('heating');

      if (System::where(['fixture_id' => $system->fixture_id])->exists()
        || System::where(['router_id' => $system->router_id])->exists()
        || System::where(['sim' => $system->sim])->exists()
        || System::where(['ups_id' => $system->ups_id])->exists()
        || System::where(['photovoltaic_id' => $system->photovoltaic_id])->exists()
        || System::where(['heating_id' => $system->heating_id])->exists())
      {
        return redirect(route('system.create'))->with('error', 'Eine oder mehrere Komponenten sind bereits in Verwendung!');
      }

      $system->save();
      return redirect(route('system.create'))->with('success', 'System erfolgreich angelegt!');
    } catch (Exception $e) {
      error_log($e);
      return redirect(route('system.create'))->with('error', 'Ein unbekannter Fehler ist aufgetreten! Sollte dies öfter passieren, kontaktieren Sie bitte einen Administrator!');
    }
  }
}
