<?php

namespace App\Http\Livewire\User;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Support\InteractsWithBanner;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Component;

class Create extends Component {
    use InteractsWithBanner;

    // used by blade / alpinejs
    public $modalId;
    public bool $isModalOpen;
    public bool $hasSmallButton;

    // inputs
    public string $name;
    public string $email;
    public string $password;
    public string $role = 'administrator';
    public array $roles;

    protected array $rules = [
        'name'            => [ 'required', 'string', 'max:255' ],
        'email'           => [ 'required', 'string', 'email', 'max:255', 'unique:users' ],
        'password'        => [ 'required', 'string' ],
        'role'            => [ 'required', 'string' ],
        'userPermissions' => [ 'array' ]
    ];

    public function mount( bool $hasSmallButton = false ) {
        $this->modalId        = 'm-new-user';
        $this->isModalOpen    = false;
        $this->hasSmallButton = $hasSmallButton || false;

        $this->name     = '';
        $this->email    = '';
        $this->password = '';
        $this->role     = 'administrator';

        $allRoles = Role::all();
        foreach ( $allRoles as $role ) {
            $this->roles[ $role->slug ] = $role->name;
        }

        $this->userPermissions = [];
        $this->allPermissions  = Permission::all();
    }


    public function render() {
        return view( 'livewire.user.create' );
    }

    public function createUser() {
        // validate user input
        $this->validate();

        DB::transaction(
            function () {
                $newUser = User::create( [
                    'name'           => htmlspecialchars( $this->name ),
                    'email'          => htmlspecialchars( $this->email ),
                    'password'       => Hash::make( $this->password ),
                    'remember_token' => Str::random( 10 ),
                ] );
                $newUser->save();

                // Save the user-role relation
                $role = Role::where( 'slug', $this->role )->first();
                $newUser->roles()->save( $role );

                // Sync the permissions - permission ids from the checkbox
                $newUser->permissions()->sync($this->userPermissions);
            },
            2
        );


        $this->banner( 'Successfully created the user "' . htmlspecialchars( $this->name ) . '"!' );

        return redirect()->route( 'user.manage' );
    }

}
