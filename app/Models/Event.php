<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Event extends Model {
    use HasFactory;

    // no need for timestamps
    public $timestamps = false;

    protected $primaryKey = "event_id";

    // the primary key is non-incrementing and a uuid string
    // if we want to use uuid as primary key
//    public $incrementing = false;
//    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'title',
        'start',
        'end',
        'address',
        'description',
        'status',
    ];


    /**
     * @return BelongsToMany
     */
    public function users(): BelongsToMany {
        return $this->belongsToMany( User::class, 'users_events' );
    }

}
