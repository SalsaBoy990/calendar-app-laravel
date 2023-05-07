<?php

namespace App\Http\Livewire\Permission;

use App\Models\Permission;
use App\Support\InteractsWithBanner;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Delete extends Component {
    use InteractsWithBanner;

    // used by blade / alpinejs
    public string $modalId;
    public bool $isModalOpen;
    public bool $hasSmallButton;

    // inputs
    public int $permissionId;
    public string $name;


    protected array $rules = [
        'permissionId' => 'required|int|min:1',
    ];

    public function mount( string $modalId, Permission $permission, bool $hasSmallButton = false ) {
        $this->modalId        = $modalId;
        $this->isModalOpen    = false;
        $this->hasSmallButton = $hasSmallButton;
        $this->permissionId   = $permission->id;
        $this->name           = $permission->name;
    }


    public function render() {
        return view( 'livewire.permission.delete' );
    }


    public function deletePermission() {
        // validate user input
        $this->validate();

        // delete role, rollback transaction if fails
        DB::transaction(
            function () {
                $permission = Permission::findOrFail( $this->permissionId );
                $permission->delete();
            },
            2
        );


        $this->banner( 'The permission with the name "' . $this->name . '" was successfully deleted.' );
        request()->session()->flash('flash.activeTab', 'Permissions');

        return redirect()->route( 'role-permission.manage' );
    }
}
