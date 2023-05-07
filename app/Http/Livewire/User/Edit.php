<?php

namespace App\Http\Livewire\User;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Support\InteractsWithBanner;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Edit extends Component {
    use InteractsWithBanner;

    // used by blade / alpinejs
    public $modalId;
    public bool $isModalOpen;
    public bool $hasSmallButton;

    // inputs
    public string $name;
    public string $email;
    public string $password;
    public User $user;
    public $userId;

    public string $role;
    public array $roles;

    protected array $rules = [
        'name'     => [ 'required', 'string', 'max:255' ],
//        'email'    => [ 'required', 'string', 'email', 'max:255', 'unique:users' ],
        'password' => [ 'string' ],
        'role'     => [ 'required', 'string' ],
        'userPermissions' => [ 'array' ]
    ];

    public function mount(string $modalId, User $user, bool $hasSmallButton = false ) {
        $this->modalId        = $modalId;
        $this->isModalOpen    = false;
        $this->hasSmallButton = $hasSmallButton || false;

        $this->user = $user;
        $this->userId = $this->user->id;
        $this->name     = $this->user->name;
        $this->email    = $this->user->email;
        $this->password = '';

        $this->userPermissions = $this->user->permissions()->get()->pluck(['id'])->toArray();
        $this->allPermissions = Permission::all();

        $roleEntity = $this->user->roles()->first();

        $this->role = $roleEntity->slug;

        $allRoles = Role::all();
        foreach ( $allRoles as $role ) {
            $this->roles[ $role->slug ] = $role->name;
        }
    }


    public function render() {
        return view( 'livewire.user.edit' );
    }

    public function updateUser() {
        // validate user input
        $this->validate();

        DB::transaction(
            function () {

                $user = User::findOrFail($this->userId);

                // only save password if a new one is supplied
                if ($this->password !== '') {
                    $user->update( [
                        'name'           => htmlspecialchars( $this->name ),
//                        'email'          => htmlspecialchars( $this->email ),
                        'password'       => Hash::make( $this->password ),
                    ] );
                } else {
                    $user->update( [
                        'name'           => htmlspecialchars( $this->name ),
//                        'email'          => htmlspecialchars( $this->email ),
                    ] );
                }

                $user->save();

                // Save new role if role is selected, and the value not equal to the current role of the user
                if ($this->role !== '' && $this->role !== $this->user->roles()->first()->slug) {
                    $user->deleteUserRole();

                    $role = Role::where( 'slug', $this->role )->first();
                    $user->roles()->save( $role );
                }

                // Sync the permissions - permission ids from the checkbox
                $user->permissions()->sync($this->userPermissions);

            },
            2
        );


        $this->banner( 'Successfully updated the user "' . htmlspecialchars( $this->name ) . '"!' );

        return redirect()->route( 'user.manage' );
    }

}
