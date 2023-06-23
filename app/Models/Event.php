<?php

namespace App\Models;

use App\Casts\HtmlSpecialCharsCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Event extends Model
{
    use HasFactory;

    // no need for timestamps
    public $timestamps = false;

    protected $primaryKey = "event_id";

    public const RECORDS_PER_PAGE = 10;

    // the primary key is non-incrementing and a uuid string
    // if we want to use uuid as primary key
//    public $incrementing = false;
//    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'client_id',
        'title',
        'start',
        'end',
        'rrule',
        'is_recurring',
        'address',
        'description',
        'status',
        'backgroundColor',
        'duration',
    ];


    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'title' => HtmlSpecialCharsCast::class,
        'start' => HtmlSpecialCharsCast::class,
        'end' => HtmlSpecialCharsCast::class,
        'rrule' => 'array',
        'address' => HtmlSpecialCharsCast::class,
        'description' => HtmlSpecialCharsCast::class,
        'status' => HtmlSpecialCharsCast::class,
        'backgroundColor' => HtmlSpecialCharsCast::class,
        'duration' => HtmlSpecialCharsCast::class,
    ];

    /**
     * @return BelongsToMany
     */
    public function workers(): BelongsToMany
    {
        return $this->belongsToMany(Worker::class, 'workers_events', 'event_id', 'worker_id');
    }

    /**
     * @return BelongsTo
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

}
