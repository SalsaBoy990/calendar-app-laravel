<?php

namespace App\Models;

use App\Casts\HtmlSpecialCharsCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Client extends Model {
    use HasFactory;

    public const RECORDS_PER_PAGE = 10;

    protected $fillable = [
        'event_id',
        'client_detail_id',
        'name',
        'address',
        'type'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'title'   => HtmlSpecialCharsCast::class,
        'address' => HtmlSpecialCharsCast::class,
    ];


    /**
     * @return HasMany
     */
    public function events(): HasMany {
        return $this->hasMany( Event::class, 'event_id', 'event_id');
    }


    /**
     * @return HasOne
     */
    public function client_detail(): HasOne {
        return $this->hasOne(ClientDetail::class, 'id', 'client_detail_id');
    }

}
