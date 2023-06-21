<?php

namespace App\Http\Livewire\Permission;

use App\Models\Permission;
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
    public Permission $permission;
    public int $permissionId;
    public Collection $allRoles;
    public array $permissionRoles;

    protected array $rules = [
        'name'            => [ 'required', 'string', 'max:255' ],
        'permissionRoles' => [ 'array' ],
    ];

    public function mount( string $modalId, Collection $roles, Permission $permission, bool $hasSmallButton = false ) {
        $this->modalId        = $modalId;
        $this->isModalOpen    = false;
        $this->hasSmallButton = $hasSmallButton || false;

        $this->permission   = $permission;
        $this->permissionId = $this->permission->id;
        $this->name         = $this->permission->name;
        $this->slug         = $this->permission->slug;

        $this->allRoles        = $roles;
        $this->permissionRoles = $this->permission->roles()->get()->pluck( [ 'id' ] )->toArray();
    }


    public function render(): Factory|View|Application {
        return view( 'livewire.permission.edit' );
    }

    public function updatePermission() {

        // if slug is changed, enable this validation
        if ( $this->slug !== $this->permission->slug ) {
            $this->rules['slug'] = [ 'required', 'string', 'max:255', 'unique:permissions' ];
        }

        // validate user input
        $this->validate();

        DB::transaction(
            function () {

                if ( $this->slug !== $this->permission->slug ) {
                    $this->permission->update( [
                        'name' => htmlspecialchars( $this->name ),
                        'slug' => htmlspecialchars( $this->slug ),
                    ] );
                } else {
                    $this->permission->update( [
                        'name' => htmlspecialchars( $this->name ),
                    ] );
                }

                $this->permission->roles()->sync( $this->permissionRoles );

                $this->permission->save();

            },
            2
        );


        $this->banner( __('Successfully updated the permission ":name"!', ['name' => htmlspecialchars( $this->name )] ) );
        request()->session()->flash( 'flash.activeTab', 'Permissions' );

        return redirect()->route( 'role-permission.manage' );
    }

}
