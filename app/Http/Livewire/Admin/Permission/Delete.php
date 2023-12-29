<?php

namespace App\Http\Livewire\Admin\Permission;

use App\Interface\Services\RolePermissionServiceInterface;
use App\Models\Permission;
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
    public int $permissionId;
    public Permission $permission;
    public string $name;


    protected array $rules = [
        'permissionId' => 'required|int|min:1',
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


    public function mount(string $modalId, Permission $permission, bool $hasSmallButton = false)
    {
        $this->modalId = $modalId;
        $this->isModalOpen = false;
        $this->hasSmallButton = $hasSmallButton;
        $this->permission = $permission;
        $this->permissionId = $permission->id;
        $this->name = $permission->name;
    }


    public function render()
    {
        return view('admin.livewire.permission.delete');
    }


    /**
     * @throws AuthorizationException
     */
    public function deletePermission()
    {
        $this->authorize('delete', [Permission::class, $this->permission]);

        // validate user input
        $this->validate();

        // delete role, rollback transaction if fails
        DB::transaction(
            function () {
                $this->rolePermissionService->deletePermission($this->permission);
            },
            2
        );


        $this->banner(__('The permission with the name ":name" was successfully deleted.',
            ['name' => htmlspecialchars($this->name)]));
        request()->session()->flash('flash.activeTab', 'Permissions');

        return redirect()->route('role-permission.manage');
    }
}
