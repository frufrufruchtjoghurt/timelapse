<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
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
        'url',
        'start_date',
        'rec_end_date',
        'video_editor_send_date',
        'video_editor',
        'pub_date',
        'patch_notes',
        'inactive',
        'inactivity_date',
        'has_imagefilm',
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
        'rec_end_date' => 'datetime',
        'video_editor_send_date' => 'datetime',
        'pub_date' => 'datetime',
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
        'rec_end_date',
        'video_editor_send_date',
        'pub_date',
    ];

    /**
     * The attributes for which can use sort in url.
     *
     * @var array
     */
    protected $allowedSorts = [
        'id',
        'name',
        'start_date',
        'rec_end_date',
        'video_editor_send_date',
        'pub_date',
        'inactive',
        'inactivity_date',
        'updated_at',
    ];

    /**
     * @return BelongsTo
     */
    public function supplyUnits()
    {
        return $this->projectSystems()->join('supply_units as s', 's.id', '=', 'project_systems.supply_unit_id')->select('s.id');
    }

    public function projectSystems()
    {
        return $this->hasMany(ProjectSystem::class);
    }

    public function features()
    {
        return $this->hasMany(Feature::class);
    }

    /**
     * @return array
     */
    public function cameras()
    {
        $ids = $this->supplyUnits()->get();
        $cameras = [];
        foreach ($ids as $id) {
            $cams = SupplyUnit::query()->where('id', '=', $id->id)->get()->first()->cameras()->get();

            foreach ($cams as $cam) {
                $cameras[] = $cam;
            }
        }

        return $cameras;
    }

    /**
     * @return HasManyThrough
     */
    public function users()
    {
        return $this->hasManyThrough(User::class, Feature::class, 'user_id', 'id', 'id', 'user_id');
    }

    public function songs()
    {
        return $this->hasManyThrough(Song::class, ProjectSong::class, 'project_id', 'id', 'id', 'song_id');
    }
}
