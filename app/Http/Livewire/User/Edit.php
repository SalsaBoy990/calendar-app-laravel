<?php

namespace App\Http\Livewire\User;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Support\InteractsWithBanner;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Edit extends Component {
    use InteractsWithBanner;
    use AuthorizesRequests;

    // used by blade / alpinejs
    public $modalId;
    public bool $isModalOpen;
    public bool $hasSmallButton;

    // inputs
    public string $name;
    public string $email;
    public string $password;
    public User $user;
    public int $userId;

    public ?int $role;
    public array $roles;

    protected array $rules = [
        'name'     => [ 'required', 'string', 'max:255' ],
        'password' => [ 'string' ],
        'role'     => [ 'required', 'integer' ],
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

        // initialize roleId property
        if (isset($this->user->role)) {
            $this->role = $this->user->role->id;
        } else {
            $this->role = null;
        }

        $allRoles = Role::all();
        foreach ( $allRoles as $role ) {
            $this->roles[ $role->id ] = $role->name;
        }
    }


    public function render() {
        return view( 'livewire.user.edit' );
    }

    public function updateUser() {
        $this->authorize('update', [User::class, $this->user]);

        // validate user input
        $this->validate();

        DB::transaction(
            function () {

                $user = User::findOrFail($this->userId);

                if (!isset($this->user->role) ) {
                    $role = Role::where( 'slug', 'worker' )->first();
                    // attach can only be used on m-m relation
                    // associate <-> dissociate
                    $user->role()->associate( $role );
                }

                // Save new role if role is selected, and the value not equal to the current role of the user
                else if ( $this->role && $this->role !== $this->user->role->id) {

                    $user->deleteUserRole();

                    $role = Role::where( 'id', $this->role )->first();
                    $user->role()->associate( $role );
                }

                // only save password if a new one is supplied
                if ($this->password !== '') {
                    $user->update( [
                        'name'           => htmlspecialchars( $this->name ),
                        'password'       => Hash::make( $this->password ),
                    ] );
                } else {
                    $user->update( [
                        'name'           => htmlspecialchars( $this->name ),
                    ] );
                }

                $user->save();


                // Sync the permissions - permission ids from the checkbox
                $user->permissions()->sync($this->userPermissions);

            },
            2
        );


        $this->banner( 'Successfully updated the user "' . htmlspecialchars( $this->name ) . '"!' );

        return redirect()->route( 'user.manage' );
    }

}
