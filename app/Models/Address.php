<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Address extends Model
{
    use HasFactory, AsSource, Filterable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'street',
        'street_nr',
        'staircase',
        'door_nr',
        'postcode',
        'city',
        'region',
        'country',
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
        'staircase' => 'integer',
        'door_nr'   => 'integer',
        'postcode'  => 'integer',
    ];

    /**
     * The attributes for which you can use filters in url.
     *
     * @var array
     */
    protected $allowedFilters = [
        'street',
        'street_nr',
        'staircase',
        'door_nr',
        'postcode',
        'city',
        'region',
        'country',
    ];

    /**
     * The attributes for which can use sort in url.
     *
     * @var array
     */
    protected $allowedSorts = [
        'street',
        'postcode',
        'city',
        'region',
        'country',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function companies()
    {
        return $this->hasMany(Company::class, 'aid');
    }

    /**
     * @return string
     */
    public function getFullAttribute(): string
    {
        $name = $this->street . ' ' . $this->street_nr;

        if ($this->staircase != null)
        {
            $name .= '/' . $this->staircase;
        }

        if ($this->door_nr != null)
        {
            $name .= '/' . $this->door_nr;
        }

        $name .= ', ' . $this->postcode . ' ' . $this->city . ', ' . $this->country;

        return $name;
    }
}
