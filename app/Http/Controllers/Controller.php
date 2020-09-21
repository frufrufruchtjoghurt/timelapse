<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Storage;

define('YEAR_MIN', date('Y-m-d', strtotime('January 01 2000')));

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function index()
    {
      $dirs = Storage::allDirectories();
      $project_coll = array();

      foreach ($dirs as $dir)
      {
        $project_coll[] = explode('_', $dir);
      }

      return view('welcome', ['projects' => $project_coll]);
    }
}
