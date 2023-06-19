<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Worker extends Model
{
    use HasFactory;

    public const RECORDS_PER_PAGE = 10;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone'
    ];


    /**
     * @return BelongsToMany
     */
    public function events(): BelongsToMany {
        return $this->belongsToMany( Event::class, 'workers_events' );
    }


    /**
     * @return HasMany
     */
    public function worker_availabilities(): HasMany {
        return $this->hasMany( WorkerAvailability::class);
    }
}
