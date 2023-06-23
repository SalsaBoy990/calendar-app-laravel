<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    use HasFactory;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
    ];


    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'roles_permissions');
    }


    /**
     * If we want to get the user relations for the roles
     * @return HasMany
     */
    /*    public function users(): HasMany {
            return $this->hasMany( User::class );
        }*/
}
