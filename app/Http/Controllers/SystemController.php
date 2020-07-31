<?php

namespace App\Http\Controllers;

use App\Fixture;
use App\Heating;
use App\Photovoltaic;
use App\Router;
use App\SimCard;
use App\System;
use App\Ups;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

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
      'fixtures' => Fixture::leftJoin('systems', 'fixtures.id', '=', 'systems.fixture_id')
        ->where('fixtures.broken', '=', false)
        ->get()
        ->loadCount(['repairs', function(Builder $query) {
          $query->where([
            'fixtures.id' => 'repairs.element_id',
            'repair.type' => 'f'
          ]);
        }]),
      'routers' => Router::leftJoin('systems', 'routers.id', '=', 'systems.router_id')
        ->where('routers.broken', '=', false)
        ->get()
        ->loadCount(['repairs', function(Builder $query) {
          $query->where([
            'routers.id' => 'repairs.element_id',
            'repair.type' => 'r'
          ]);
        }]),
      'sims' => SimCard::leftJoin('systems', 'sim_cards.id', '=', 'systems.sim_id')
        ->where('sim_cards.broken', '=', false)
        ->get()
        ->loadCount(['repairs', function(Builder $query) {
          $query->where([
            'sim_cards.id' => 'repairs.element_id',
            'repair.type' => 's'
          ]);
        }]),
      'ups' => Ups::leftJoin('systems', 'ups.id', '=', 'systems.ups_id')
        ->where('ups.broken', '=', false)
        ->get()
        ->loadCount(['repairs', function(Builder $query) {
          $query->where([
            'ups.id' => 'repairs.element_id',
            'repair.type' => 'u'
          ]);
        }]),
      'heatings' => Heating::leftJoin('systems', 'heatings.id', '=', 'systems.heating_id')
        ->where('heatings.broken', '=', false)
        ->get()
        ->loadCount(['repairs', function(Builder $query) {
          $query->where([
            'heatings.id' => 'repairs.element_id',
            'repair.type' => 'h'
          ]);
        }]),
      'photovoltaics' => Photovoltaic::leftJoin('systems', 'photovoltaics.id', '=', 'systems.photovoltaic_id')
        ->where('photovoltaics.broken', '=', false)
        ->get()
        ->loadCount(['repairs', function(Builder $query) {
          $query->where([
            'photovoltaics.id' => 'repairs.element_id',
            'repair.type' => 'p'
          ]);
        }])
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
