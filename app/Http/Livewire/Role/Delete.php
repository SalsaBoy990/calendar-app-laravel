<?php

namespace App\Http\Livewire\Role;

use App\Models\Role;
use App\Support\InteractsWithBanner;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Delete extends Component
{
    use InteractsWithBanner;
    use AuthorizesRequests;

    // used by blade / alpinejs
    public string $modalId;
    public bool $isModalOpen;
    public bool $hasSmallButton;

    // inputs
    public int $roleId;
    public Role $role;
    public string $name;


    protected array $rules = [
        'roleId' => 'required|int|min:1',
    ];

    public function mount(string $modalId, Role $role, bool $hasSmallButton = false)
    {
        $this->modalId = $modalId;
        $this->isModalOpen = false;
        $this->hasSmallButton = $hasSmallButton;
        $this->role = $role;
        $this->roleId = $role->id;
        $this->name = $role->name;
    }


    public function render()
    {
        return view('livewire.role.delete');
    }


    public function deleteRole()
    {
        $this->authorize('delete', [Role::class, $this->role]);

        // validate user input
        $this->validate();

        // delete role, rollback transaction if fails
        DB::transaction(
            function () {
                $this->role->delete();
            },
            2
        );


        $this->banner(__('The role with the name ":name" was successfully deleted.',
            ['name' => htmlspecialchars($this->name)]));
        return redirect()->route('role-permission.manage');
    }
}
