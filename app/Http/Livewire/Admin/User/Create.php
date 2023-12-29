<?php

namespace App\Http\Livewire\Admin\User;

use App\Interface\Repository\UserRepositoryInterface;
use App\Interface\Services\RolePermissionServiceInterface;
use App\Models\User;
use App\Support\InteractsWithBanner;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
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
    public string $email;
    public string $password;
    public ?int $role;
    public array $rolesArray;

    protected array $rules = [
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => ['required', 'string'],
        'role' => ['required', 'integer'],
    ];


    /**
     * @var RolePermissionServiceInterface
     */
    private RolePermissionServiceInterface $rolePermissionService;


    /**
     * @var UserRepositoryInterface
     */
    private UserRepositoryInterface $userRepository;


    /**
     * @param  RolePermissionServiceInterface  $rolePermissionService
     * @param  UserRepositoryInterface  $userRepository
     */
    public function boot(RolePermissionServiceInterface $rolePermissionService, UserRepositoryInterface $userRepository)
    {
        $this->rolePermissionService = $rolePermissionService;
        $this->userRepository = $userRepository;
    }


    public function mount(Collection $roles, bool $hasSmallButton = false)
    {
        $this->modalId = 'm-new-user';
        $this->isModalOpen = false;
        $this->hasSmallButton = $hasSmallButton;

        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->role = null;

        $allRoles = $roles;
        foreach ($allRoles as $role) {
            $this->rolesArray[$role->id] = $role->name;
        }

    }


    public function render()
    {
        return view('admin.livewire.user.create');
    }


    /**
     * @throws AuthorizationException
     */
    public function createUser()
    {
        $this->authorize('create', User::class);

        // validate user input
        $this->validate();

        DB::transaction(
            function () {
                $newUser = $this->userRepository->createUser([
                    'name' => htmlspecialchars($this->name),
                    'email' => htmlspecialchars($this->email),
                    'password' => Hash::make($this->password),
                    'remember_token' => Str::random(10),
                ]);


                // Save the user-role relation
                $role = $this->rolePermissionService->getRoleById($this->role);
                $this->rolePermissionService->associateRoleWithUser($role, $newUser);
            },
            2
        );


        $this->banner(__('Successfully created the user ":name"!', ['name' => htmlspecialchars($this->name)]));

        return redirect()->route('user.manage');
    }

}
