<?php

namespace App\Http\Livewire\Admin\Role;

use App\Interface\Services\RolePermissionServiceInterface;
use App\Models\Role;
use App\Support\InteractsWithBanner;
use Illuminate\Auth\Access\AuthorizationException;
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


    /**
     * @var RolePermissionServiceInterface
     */
    private RolePermissionServiceInterface $rolePermissionService;


    /**
     * @param  RolePermissionServiceInterface  $rolePermissionService
     */
    public function boot(RolePermissionServiceInterface $rolePermissionService)
    {
        $this->rolePermissionService = $rolePermissionService;
    }


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
        return view('admin.livewire.role.delete');
    }


    /**
     * @throws AuthorizationException
     */
    public function deleteRole()
    {
        $this->authorize('delete', [Role::class, $this->role]);

        // validate user input
        $this->validate();

        // delete role, rollback transaction if fails
        DB::transaction(
            function () {
                $this->rolePermissionService->deleteRole($this->role);
            },
            2
        );


        $this->banner(__('The role with the name ":name" was successfully deleted.',
            ['name' => htmlspecialchars($this->name)]));
        return redirect()->route('role-permission.manage');
    }
}
