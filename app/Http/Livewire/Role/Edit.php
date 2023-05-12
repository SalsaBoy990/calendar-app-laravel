<?php

namespace App\Http\Livewire\Role;

use App\Models\Permission;
use App\Models\Role;
use App\Support\InteractsWithBanner;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Edit extends Component {
    use InteractsWithBanner;
    use AuthorizesRequests;

    // used by blade / alpinejs
    public string $modalId;
    public bool $isModalOpen;
    public bool $hasSmallButton;

    // inputs
    public string $name;
    public string $slug;
    public Role $role;
    public int $roleId;
    public  Collection $allPermissions;
    public  array $rolePermissions;

    protected array $rules = [
        'name' => [ 'required', 'string', 'max:255' ],
        'rolePermissions' => [ 'array']
    ];

    public function mount( string $modalId, Role $role, bool $hasSmallButton = false ) {
        $this->modalId        = $modalId;
        $this->isModalOpen    = false;
        $this->hasSmallButton = $hasSmallButton || false;

        $this->role   = $role;
        $this->roleId = $this->role->id;
        $this->name   = $this->role->name;
        $this->slug   = $this->role->slug;

        $this->rolePermissions = $this->role->permissions()->get()->pluck(['id'])->toArray();
        $this->allPermissions = Permission::all();
    }


    public function render(): Factory|View|Application {
        return view( 'livewire.role.edit' );
    }

    public function updateRole() {

        $role = Role::findOrFail( $this->roleId );
        $this->authorize('update', [Role::class, $role]);

        // if slug is changed, enable this validation
        if ( $this->slug !== $this->role->slug ) {
            $this->rules['slug'] = [ 'required', 'string', 'max:255', 'unique:roles' ];
        }

        // validate user input
        $this->validate();


        DB::transaction(
            function () use ($role) {

                if ( $this->slug === $this->role->slug ) {
                    $role->update( [
                        'name' => htmlspecialchars( $this->name ),
                        'slug' => htmlspecialchars( $this->slug ),
                    ] );
                } else {
                    $role->update( [
                        'name' => htmlspecialchars( $this->name ),
                    ] );
                }

                $role->permissions()->sync($this->rolePermissions);

                $role->save();

            },
            2
        );


        $this->banner( 'Successfully updated the role "' . htmlspecialchars( $this->name ) . '"!' );

        return redirect()->route( 'role-permission.manage' );
    }

}
