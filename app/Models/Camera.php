<?php

namespace App\Models;

use App\Orchid\Presenters\CameraPresenter;
use DateTime;
use DateTimeZone;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
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
        'name',
        'purchase_date',
        'broken',
        'supply_unit_id',
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
        'name',
        'times_used',
    ];

    /**
     * The attributes for which can use sort in url.
     *
     * @var array
     */
    protected $allowedSorts = [
        'serial_nr',
        'model',
        'name',
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

    public function supplyUnit()
    {
        return $this->belongsTo(SupplyUnit::class);
    }

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

    public function getFullAttribute(): string
    {
        return $this->model . ": " . $this->name . ", " . $this->serial_nr . " | Verwendungen: " . $this->times_used
            . ' | Alter: ' . $this->age();
    }

    public function scopeUnit(Builder $query)
    {
        return $query->where('broken', false)->whereDoesntHave('supplyUnit');
    }
}
