<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PhotovoltaicController extends Controller
{
  public function index()
  {

  }

    /**
   * Show page for photovoltaic creation
   *
   *  @return \Illuminate\Contracts\Support\Renderable
   */
  public function create()
  {
    return view('photovoltaic.create');
  }
}
