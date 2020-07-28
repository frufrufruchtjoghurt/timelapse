<?php

namespace App\Http\Controllers;

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
    return view('fixture.create');
  }
}
