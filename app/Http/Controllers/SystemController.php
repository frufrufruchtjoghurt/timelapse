<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SystemController extends Controller
{
  public function index()
  {

  }

    /**
   * Go to the company creator
   *
   *  @return \Illuminate\Contracts\Support\Renderable
   */
  public function create()
  {
    return view('system.create');
  }
}
