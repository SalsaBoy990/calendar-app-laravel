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
    public Collection $allPermissions;
    public array $rolePermissions;

    protected array $rules = [
        'name' => ['required', 'string', 'max:255'],
        'slug' => ['required', 'string', 'max:255', 'unique:roles'],
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


    public function mount(Collection $permissions, bool $hasSmallButton = false)
    {
        $this->modalId = 'm-new-role';
        $this->isModalOpen = false;
        $this->hasSmallButton = $hasSmallButton;

        $this->name = '';
        $this->slug = '';

        $this->rolePermissions = [];
        $this->allPermissions = $permissions;
    }


    public function render(): Factory|View|Application
    {
        return view('admin.livewire.role.create');
    }

    /**
     * @throws AuthorizationException
     */
    public function createRole()
    {
        $this->authorize('create', Role::class);

        // validate user input
        $this->validate();

        DB::transaction(
            function () {
                $newRole = $this->rolePermissionService->createRole([
                    'name' => htmlspecialchars($this->name),
                    'slug' => htmlspecialchars($this->slug),
                ]);

                $this->rolePermissionService->syncPermissionsToRole($newRole, $this->rolePermissions);
            },
            2
        );


        $this->banner(__('Successfully created the role ":name"!', ['name' => htmlspecialchars($this->name)]));

        return redirect()->route('role-permission.manage');
    }


}
