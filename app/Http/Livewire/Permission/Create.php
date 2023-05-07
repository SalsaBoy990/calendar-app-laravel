<?php

namespace App\Http\Livewire\Permission;

use App\Models\Permission;
use App\Models\Role;
use App\Support\InteractsWithBanner;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Create extends Component {
    use InteractsWithBanner;

    // used by blade / alpinejs
    public $modalId;
    public bool $isModalOpen;
    public bool $hasSmallButton;

    // inputs
    public string $name;
    public string $slug;
    public Collection $allRoles;
    public array $permissionRoles;

    protected array $rules = [
        'name' => [ 'required', 'string', 'max:255' ],
        'slug' => [ 'required', 'string', 'max:255', 'unique:permissions' ],
        'permissionRoles' => [ 'array' ],
    ];

    public function mount( bool $hasSmallButton = false ) {
        $this->modalId        = 'm-new-permission';
        $this->isModalOpen    = false;
        $this->hasSmallButton = $hasSmallButton || false;

        $this->name = '';
        $this->slug = '';

        $this->allRoles = Role::all();
        $this->permissionRoles = [];
    }


    public function render(): Factory|View|Application {
        return view( 'livewire.permission.create' );
    }

    public function createPermission() {
        // validate user input
        $this->validate();

        DB::transaction(
            function () {
                $newPermission = Permission::create( [
                    'name' => htmlspecialchars( $this->name ),
                    'slug' => htmlspecialchars( $this->slug ),
                ] );
                $newPermission->save();

                $newPermission->roles()->sync($this->permissionRoles);
            },
            2
        );


        $this->banner( 'Successfully created the permission "' . htmlspecialchars( $this->name ) . '"!' );
        request()->session()->flash('flash.activeTab', 'Permissions');

        return redirect()->route( 'role-permission.manage' );
    }


}
