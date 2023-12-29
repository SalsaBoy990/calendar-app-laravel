<?php

namespace App\Http\Livewire\Admin\Permission;

use App\Interface\Services\RolePermissionServiceInterface;
use App\Models\Permission;
use App\Support\InteractsWithBanner;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Create extends Component
{
    use InteractsWithBanner;
    use AuthorizesRequests;

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
        'name' => ['required', 'string', 'max:255'],
        'slug' => ['required', 'string', 'max:255', 'unique:permissions'],
        'permissionRoles' => ['array'],
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


    public function mount(Collection $roles, bool $hasSmallButton = false)
    {
        $this->modalId = 'm-new-permission';
        $this->isModalOpen = false;
        $this->hasSmallButton = $hasSmallButton;

        $this->name = '';
        $this->slug = '';

        $this->allRoles = $roles;
        $this->permissionRoles = [];
    }


    public function render(): Factory|View|Application
    {
        return view('admin.livewire.permission.create');
    }

    /**
     * @throws AuthorizationException
     */
    public function createPermission()
    {
        $this->authorize('create', Permission::class);

        // validate user input
        $this->validate();

        DB::transaction(
            function () {
                $newPermission = $this->rolePermissionService->createPermission([
                    'name' => htmlspecialchars($this->name),
                    'slug' => htmlspecialchars($this->slug),
                ]);

                $this->rolePermissionService->syncRolesToPermission($newPermission, $this->permissionRoles);
            },
            2
        );


        $this->banner(__('Successfully created the permission ":name"!', ['name' => htmlspecialchars($this->name)]));
        request()->session()->flash('flash.activeTab', 'Permissions');

        return redirect()->route('role-permission.manage');
    }


}
