<?php

namespace App\Models;

use App\Orchid\Presenters\FixturePresenter;
use DateTime;
use DateTimeZone;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Log;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Fixture extends Model
{
    use HasFactory, AsSource, Filterable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
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
        'model',
    ];

    /**
     * The attributes for which can use sort in url.
     *
     * @var array
     */
    protected $allowedSorts = [
        'model',
        'purchase_date',
        'times_used',
        'broken',
        'updated_at',
    ];

    /**
     * @return FixturePresenter
     */
    public function presenter()
    {
        return new FixturePresenter($this);
    }

    /**
     * @return HasOne
     */
    public function supplyUnit()
    {
        return $this->hasOne(SupplyUnit::class);
    }

    /**
     * @return HasManyThrough
     */
    public function projects()
    {
        if ($this->supplyUnit()->exists())
            return $this->supplyUnit()->get()->first()->projects();
        return null;
    }

    public function age()
    {
        $tz = new DateTimeZone('Europe/Vienna');
        return round(now($tz)->diffInMonths(new DateTime($this->purchase_date)) / 12, 1);
    }

    /**
     * @return string
     */
    public function getFullAttribute(): string
    {
        return $this->model . " | Verwendungen: " . $this->times_used
            . ' | Alter: ' . $this->age();
    }

    /**
     * @param Builder $query
     *
     * @return mixed
     */
    public function scopeAvailable(Builder $query)
    {
        return $query->where('broken', false)->whereDoesntHave('supplyUnit');
    }
}
