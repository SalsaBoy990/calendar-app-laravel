<?php

namespace App\Http\Livewire\Admin\Role;

use App\Interface\Services\RolePermissionServiceInterface;
use App\Models\Role;
use App\Support\InteractsWithBanner;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Edit extends Component
{
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
    public Collection $allPermissions;
    public array $rolePermissions;

    protected array $rules = [
        'name' => ['required', 'string', 'max:255'],
        'rolePermissions' => ['array']
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


    public function mount(string $modalId, Role $role, Collection $permissions, bool $hasSmallButton = false)
    {
        $this->modalId = $modalId;
        $this->isModalOpen = false;
        $this->hasSmallButton = $hasSmallButton;

        $this->role = $role;
        $this->roleId = $this->role->id;
        $this->name = $this->role->name;
        $this->slug = $this->role->slug;

        $this->rolePermissions = $this->rolePermissionService->getRelatedIds($this->role->permissions);
        $this->allPermissions = $permissions;
    }




    public function render(): Factory|View|Application
    {
        return view('admin.livewire.role.edit');
    }

    /**
     * @throws AuthorizationException
     */
    public function updateRole()
    {
        $this->authorize('update', [Role::class, $this->role]);

        $role = $this->rolePermissionService->getRoleById($this->roleId);
        $this->authorize('update', [Role::class, $role]);

        // if slug is changed, enable this validation
        if ($this->slug !== $this->role->slug) {
            $this->rules['slug'] = ['required', 'string', 'max:255', 'unique:roles'];
        }

        // validate user input
        $this->validate();


        DB::transaction(
            function () use ($role) {

                if ($this->slug === $this->role->slug) {

                    $this->rolePermissionService->updateRole($role, [
                        'name' => htmlspecialchars($this->name),
                        'slug' => htmlspecialchars($this->slug),
                    ]);
                } else {
                    $this->rolePermissionService->updateRole($role, [
                        'name' => htmlspecialchars($this->name),
                    ]);
                }

                $this->rolePermissionService->syncPermissionsToRole($role, $this->rolePermissions);
            },
            2
        );


        $this->banner(__('Successfully updated the role ":name"!', ['name' => htmlspecialchars($this->name)]));

        return redirect()->route('role-permission.manage');
    }

}
