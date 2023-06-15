<?php

namespace App\Models;

use App\Casts\HtmlSpecialCharsCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkerAvailability extends Model
{
    use HasFactory;

    // no need for timestamps
    public $timestamps = false;

    protected $primaryKey = "availability_id";

    // the primary key is non-incrementing and a uuid string
    // if we want to use uuid as primary key
//    public $incrementing = false;
//    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'start',
        'end',
        'description',
        'backgroundColor',
    ];


    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start'           => HtmlSpecialCharsCast::class,
        'end'             => HtmlSpecialCharsCast::class,
        'description'     => HtmlSpecialCharsCast::class,
        'backgroundColor' => HtmlSpecialCharsCast::class,
    ];


    /**
     * @return BelongsTo
     */
/*    public function user(): BelongsTo {
        return $this->belongsTo( User::class, 'user_id');
    }*/

    /**
     * @return BelongsTo
     */
    public function worker(): BelongsTo {
        return $this->belongsTo( Worker::class, 'worker_id');
    }

}
