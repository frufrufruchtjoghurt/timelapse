<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Symlink extends Model
{
    use HasFactory;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_persistent' => 'bool',
        'is_latest' => 'bool',
        'is_movie' => 'bool',
    ];

    public function user() {
        $this->belongsTo(User::class);
    }

    public function camera() {
        $this->belongsTo(Camera::class);
    }
}
