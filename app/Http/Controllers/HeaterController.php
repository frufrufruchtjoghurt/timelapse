<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HeaterController extends Controller
{
  public function index()
  {

  }

    /**
   * Show page for heater creation
   *
   *  @return \Illuminate\Contracts\Support\Renderable
   */
  public function create()
  {
    return view('heater.create');
  }
}
