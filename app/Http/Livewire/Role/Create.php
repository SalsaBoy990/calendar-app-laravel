<?php

namespace App\Http\Livewire\Role;

use App\Models\Role;
use App\Support\InteractsWithBanner;
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

    public function mount(Collection $permissions, bool $hasSmallButton = false,)
    {
        $this->modalId = 'm-new-role';
        $this->isModalOpen = false;
        $this->hasSmallButton = $hasSmallButton || false;

        $this->name = '';
        $this->slug = '';

        $this->rolePermissions = [];
        $this->allPermissions = $permissions;
    }


    public function render(): Factory|View|Application
    {
        return view('livewire.role.create');
    }

    public function createRole()
    {
        $this->authorize('create', Role::class);

        // validate user input
        $this->validate();

        DB::transaction(
            function () {
                $newRole = Role::create([
                    'name' => htmlspecialchars($this->name),
                    'slug' => htmlspecialchars($this->slug),
                ]);
                $newRole->save();

                /*                $roles = Permission::all();
                                $newRole->permissions()->saveMany( $roles );*/
                $newRole->permissions()->sync($this->rolePermissions);
            },
            2
        );


        $this->banner(__('Successfully created the role ":name"!', ['name' => htmlspecialchars($this->name)]));

        return redirect()->route('role-permission.manage');
    }


}
