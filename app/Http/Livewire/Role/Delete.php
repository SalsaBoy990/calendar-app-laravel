<?php

namespace App\Http\Livewire\Role;

use App\Models\Role;
use App\Support\InteractsWithBanner;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Delete extends Component
{
    use InteractsWithBanner;

    // used by blade / alpinejs
    public string $modalId;
    public bool $isModalOpen;
    public bool $hasSmallButton;

    // inputs
    public int $roleId;
    public string $name;


    protected array $rules = [
        'roleId' => 'required|int|min:1',
    ];

    public function mount(string $modalId, Role $role, bool $hasSmallButton = false)
    {
        $this->modalId = $modalId;
        $this->isModalOpen = false;
        $this->hasSmallButton = $hasSmallButton;
        $this->roleId = $role->id;
        $this->name = $role->name;
    }


    public function render()
    {
        return view('livewire.role.delete');
    }


    public function deleteRole()
    {
        // validate user input
        $this->validate();

        // delete role, rollback transaction if fails
        DB::transaction(
            function () {
                $role = Role::findOrFail($this->roleId);
                $role->delete();
            },
            2
        );


        $this->banner('The role with the name "' . $this->name .  '" was successfully deleted.');
        return redirect()->route('role-permission.manage');
    }
}
