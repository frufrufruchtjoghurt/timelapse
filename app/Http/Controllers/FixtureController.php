<?php

namespace App\Http\Controllers;

use App\Fixture;
use Exception;
use Illuminate\Http\Request;

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

  public function store()
  {
    try
    {
      $fixture = new Fixture();

      $fixture->serial_nr = request('serial_nr_f');
      $fixture->model = request('type_f');

      setlocale(LC_TIME, ['de_at', 'de_de', 'de']);
      $year = request('build_year_f');
      if ($year > strftime('%Y') || $year < YEAR_MIN)
      {
        return redirect(route('system.fixture.create'))->with('error', 'Es wurde kein gültiges Jahr angegeben!');
      }
      $fixture->build_year = $year;

      $first_match = Fixture::where([
        'serial_nr' => $fixture->serial_nr,
        'model' => $fixture->model,
        'build_year' => $fixture->build_year
      ]);

      if ($first_match->exists())
      {
        return redirect(route('system.fixture.create'))->with('error', 'Ein Gehäuse mit der Konfiguration wurde bereits angelegt!');
      }

      $fixture->save();
      return redirect(route('system.fixture.create'))->with('success', 'Gehäuse erfolgreich angelegt!');
    }
    catch(Exception $e)
    {
      error_log($e);
      return redirect(route('system.fixture.create'))->with('error', 'Ein unbekannter Fehler ist aufgetreten! Sollte dies öfter passieren, kontaktieren Sie bitte einen Administrator!');
    }
  }
}
