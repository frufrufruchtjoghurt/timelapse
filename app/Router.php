<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Router extends Model
{
    //
    protected $casts = [
      'broken' => 'boolean',
    ];
}
