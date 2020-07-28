<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SimController extends Controller
{
  public function index()
  {

  }

    /**
   * Show page for SIM creation
   *
   *  @return \Illuminate\Contracts\Support\Renderable
   */
  public function create()
  {
    return view('sim.create');
  }
}
