<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Project extends Model
{
    use HasFactory, AsSource, Filterable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'cid',
        'sid',
        'vpn_ip',
        'longitude',
        'latitude',
        'start_date',
        'end_date',
        'inactive',
        'inactivity_date',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'inactive' => 'bool',
        'inactivity_date' => 'datetime',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    /**
     * The attributes for which you can use filters in url.
     *
     * @var array
     */
    protected $allowedFilters = [
        'id',
        'name',
        'start_date',
        'end_date',
    ];

    /**
     * The attributes for which can use sort in url.
     *
     * @var array
     */
    protected $allowedSorts = [
        'id',
        'name',
        'vpn_ip',
        'start_date',
        'end_date',
        'inactive',
        'inactivity_date',
        'updated_at',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function system()
    {
        return $this->belongsTo(System::class, 'sid');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function camera()
    {
        return $this->belongsTo(Camera::class, 'cid');
    }

    public function users()
    {
        return $this->hasManyThrough(User::class, Feature::class, 'pid', 'id', 'id', 'uid');
    }
}
