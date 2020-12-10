<?php

namespace App\Models;

use App\Orchid\Presenters\PhotovoltaicPresenter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Photovoltaic extends Model
{
    use HasFactory, AsSource, Filterable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'serial_nr',
        'model',
        'purchase_date',
        'broken',
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
        'broken' => 'bool',
        'purchase_date' => 'datetime',
    ];

    /**
     * The attributes for which you can use filters in url.
     *
     * @var array
     */
    protected $allowedFilters = [
        'serial_nr',
        'model',
    ];

    /**
     * The attributes for which can use sort in url.
     *
     * @var array
     */
    protected $allowedSorts = [
        'serial_nr',
        'model',
        'purchase_date',
        'broken',
        'updated_at',
    ];

    /**
     * @return PhotovoltaicPresenter
     */
    public function presenter()
    {
        return new PhotovoltaicPresenter($this);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function system()
    {
        return $this->hasOne(System::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function projects()
    {
        return $this->hasManyThrough(Project::class, System::class, 'photovoltaic_id', 'sid');
    }

    /**
     * @return string
     */
    public function getFullAttribute(): string
    {
        return $this->model . ', ' . $this->purchase_date->toDateString() . ', ' . $this->serial_nr;
    }

    /**
     * @param Builder $query
     *
     * @return mixed
     */
    public function scopeAvailable(Builder $query)
    {
        return $query->where('broken', false)->whereDoesntHave('system');
    }
}
