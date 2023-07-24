<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientDetail extends Model
{
    use HasFactory;
    use SoftDeletes;


    protected $fillable = [
        'contact_person',
        'phone_number',
        'email',
        'tax_number',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'id', 'client_id');
    }
}
