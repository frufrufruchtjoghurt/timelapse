<?php

namespace App\Models;

use App\Orchid\Presenters\UpsPresenter;
use DateTime;
use DateTimeZone;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Ups extends Model
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
        'times_used',
        'updated_at',
    ];

    /**
     * @return UpsPresenter
     */
    public function presenter()
    {
        return new UpsPresenter($this);
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
        return $this->model . ": " . $this->serial_nr . " | Verwendungen: " . $this->times_used
            . ' | ' . $this->purchase_date->toDateString();
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
