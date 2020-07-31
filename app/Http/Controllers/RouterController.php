<?php

namespace App\Http\Controllers;

use App\Router;
use Exception;
use Illuminate\Http\Request;

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

      setlocale(LC_TIME, ['de_at', 'de_de', 'de']);
      $year = request('build_year_r');
      if ($year > strftime('%Y') || $year < YEAR_MIN)
      {
        return redirect(route('router.create'))->with('error', 'Es wurde kein gültiges Jahr angegeben!');
      }
      $router->build_year = $year;

      $first_match = Router::where([
        'serial_nr' => $router->serial_nr,
        'model' => $router->model,
        'build_year' => $router->build_year
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
