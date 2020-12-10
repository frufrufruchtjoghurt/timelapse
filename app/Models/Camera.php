<?php

namespace App\Models;

use App\Orchid\Presenters\CameraPresenter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Camera extends Model
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
     * @return CameraPresenter
     */
    public function presenter()
    {
        return new CameraPresenter($this);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function projects()
    {
        return $this->hasMany(Project::class, 'cid');
    }

    public function getFullAttribute(): string
    {
        return $this->model . ", " . $this->serial_nr;
    }

    public function scopeAvailable(Builder $query)
    {
        Log::debug($query->leftJoin('projects as p', 'p.cid', '=', 'cameras.id')
            ->whereDate('p.end_date', '<=', date('Y-m-d'))
            ->orWhereNull('p.project_nr')->toSql());
        return $query->joinSub(Project::where('projects.end_date', '=', null), 'projects', 'cameras.id', '=', 'projects.cid', 'outer');
    }
}
