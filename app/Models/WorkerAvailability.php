<?php

namespace App\Models;

use App\Casts\HtmlSpecialCharsCast;
use App\Trait\DateTimeConverter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkerAvailability extends Model
{
    use HasFactory;
    use DateTimeConverter;

    // no need for timestamps
    public $timestamps = false;

    protected $primaryKey = "id";

    public const TIMEZONE = 'Europe/Budapest';

    // the primary key is non-incrementing and an uuid string
    // if we want to use uuid as primary key
    // public $incrementing = false;
    // protected $keyType = 'string';

    protected $fillable = [
        'id',
        'start',
        'end',
        'description',
    ];


    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start' => 'datetime', // ISO8601 (fullcalendar needs this format, mysql does not support it)
        'end' => 'datetime', // ISO8601
        'description' => HtmlSpecialCharsCast::class,
        'backgroundColor' => HtmlSpecialCharsCast::class,
    ];


    /**
     * @return BelongsTo
     */
    public function worker(): BelongsTo
    {
        return $this->belongsTo(Worker::class, 'worker_id');
    }

}
