<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Mail\SendCodeMail;
use App\Trait\HasRolesAndPermissions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;
use Laravel\Sanctum\HasApiTokens;

final class User extends Authenticatable {
    use HasApiTokens, HasFactory, Notifiable, HasRolesAndPermissions;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'enable_2fa',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Creates a 2FA code for the user
     * @return void
     */
    public function generateCode() {
        $code = rand( 1000, 9999 );

        UserCode::updateOrCreate(
            [ 'user_id' => auth()->id() ],
            [ 'code' => $code ]
        );

        try {

            $details = [
                'title' => __('Mail from Calendar App'),
                'code' => $code
            ];

            // Send the code in email
            Mail::to(auth()->user()->email)->send(new SendCodeMail($details));


        } catch (\Exception $e) {
            info("Error: " . $e->getMessage());
            dd($e);
        }
    }
}
