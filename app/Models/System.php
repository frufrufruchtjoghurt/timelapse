<?php

namespace App\Models;

use App\Orchid\Presenters\SystemPresenter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class System extends Model
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
        'sim_card_id',
        'ups_id',
        'heating_id',
        'photovoltaic_id',
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
    protected $casts = [];

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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function projects()
    {
        return $this->hasMany(Project::class, 'sid');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function fixture()
    {
        return $this->belongsTo(Fixture::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function router()
    {
        return $this->belongsTo(Router::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sim_card()
    {
        return $this->belongsTo(SimCard::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ups()
    {
        return $this->belongsTo(Ups::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function heating()
    {
        return $this->belongsTo(Heating::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function photovoltaic()
    {
        return $this->belongsTo(Photovoltaic::class);
    }

    public function presenter()
    {
        return new SystemPresenter($this);
    }

    public function getFullAttribute() : string
    {
        $name = $this->fixture()->get()->first()->model . ", " . $this->router()->get()->first()->model . ", " .
            $this->sim_card()->get()->first()->contract . ", " . $this->ups()->get()->first()->model;

        if (!empty($this->heating()->get()->first()))
        {
            $name .= ", " . $this->heating()->get()->first()->model;
        }

        if (!empty($this->photovoltaic()->get()->first()))
        {
            $name .= ", " . $this->photovoltaic()->get()->first()->model;
        }

        return $name;
    }
}
