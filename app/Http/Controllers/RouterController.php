<?php

namespace App\Http\Controllers;

use App\Router;
use Exception;
use Illuminate\Http\Request;

define('YEAR_MIN', date('Y-m-d', strtotime('January 01 2000')));

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
}
