<?php

namespace App\Models;

use App\Orchid\Presenters\SupplyUnitPresenter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Facades\Log;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class SupplyUnit extends Model
{
    use HasFactory, AsSource, Filterable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fixture_id',
        'router_id',
        'ups_id',
        'has_heating',
        'has_cooling',
        'photovoltaic_id',
        'serial_nr',
        'details',
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
        'has_heating' => 'boolean',
        'has_cooling' => 'boolean',
    ];

    /**
     * The attributes for which you can use filters in url.
     *
     * @var array
     */
    protected $allowedFilters = [];

    /**
     * The attributes for which can use sort in url.
     *
     * @var array
     */
    protected $allowedSorts = [];

    /**
     * @return HasMany
     */
    public function projectSystems()
    {
        return $this->hasMany(ProjectSystem::class);
    }

    /**
     * @return HasMany
     */
    public function projects()
    {
        return $this->projectSystems()->join('projects as p', 'p.id', '=', 'project_systems.project_id')->select('p.*');
    }

    public function cameras()
    {
        return $this->hasMany(Camera::class);
    }

    /**
     * @return BelongsTo
     */
    public function fixture()
    {
        return $this->belongsTo(Fixture::class);
    }

    /**
     * @return BelongsTo
     */
    public function router()
    {
        return $this->belongsTo(Router::class);
    }

    /**
     * @return BelongsTo
     */
    public function sim_card()
    {
        return $this->router()->get()->first()->simCard();
    }

    /**
     * @return BelongsTo
     */
    public function ups()
    {
        return $this->belongsTo(Ups::class);
    }

    /**
     * @return BelongsTo
     */
    public function photovoltaic()
    {
        return $this->belongsTo(Photovoltaic::class);
    }

    /**
     * @return SupplyUnitPresenter
     */
    public function presenter()
    {
        return new SupplyUnitPresenter($this);
    }

    public function getFullAttribute(): string
    {
        $name = $this->fixture()->get()->first()->model . ", " . $this->router()->get()->first()->model . ", " .
            $this->sim_card()->get()->first()->contract;

        if (!empty($this->ups()->get()->first())) {
            $name .= ", " . $this->ups()->get()->first()->model;
        }

        if ($this->has_heating) {
            $name .= ", Heizung";
        }

        if ($this->has_cooling) {
            $name .= ", Lüftung";
        }

        if (!empty($this->photovoltaic()->get()->first())) {
            $name .= ", " . $this->photovoltaic()->get()->first()->model;
        }

        return $name;
    }

    public function scopeAvailable(Builder $query, string $end_date)
    {
        Log::debug($end_date);
        Log::debug($query->leftJoin('project_systems as s', 's.supply_unit_id', '=', 'supply_units.id')
            ->leftJoin('projects as p', 'p.id', '=', 's.project_id')
            ->where('p.id', '=', null)
            ->orWhere('p.rec_end_date', '<', $end_date)->select('supply_units.*')->toSql());

        return $query->leftJoin('project_systems as s', 's.supply_unit_id', '=', 'supply_units.id')
            ->leftJoin('projects as p', 'p.id', '=', 's.project_id')
            ->orWhere('broken', false)->select('supply_units.*');
    }
}
