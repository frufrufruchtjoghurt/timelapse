<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UpsController extends Controller
{
  public function index()
  {

  }

    /**
   * Show page for UPS creation
   *
   *  @return \Illuminate\Contracts\Support\Renderable
   */
  public function create()
  {
    return view('ups.create');
  }
}
