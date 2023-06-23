<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ClientDetail extends Model
{
    use HasFactory;


    protected $fillable = [
        'client_id',
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
