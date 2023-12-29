<?php

namespace App\Http\Livewire\Admin\User;

use App\Interface\Repository\UserRepositoryInterface;
use App\Models\Role;
use App\Models\User;
use App\Support\InteractsWithBanner;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Edit extends Component
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
    public User $user;
    public int $userId;

    public ?int $role;
    public array $rolesArray;

    protected array $rules = [
        'name' => ['required', 'string', 'max:255'],
        'password' => ['string'],
        'role' => ['required', 'integer'],
    ];


    /**
     * @var UserRepositoryInterface
     */
    private UserRepositoryInterface $userRepository;


    /**
     * @param  UserRepositoryInterface  $userRepository
     */
    public function boot(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }


    public function mount(
        string $modalId,
        User $user,
        Collection $roles,
        bool $hasSmallButton = false
    ) {
        $this->modalId = $modalId;
        $this->isModalOpen = false;
        $this->hasSmallButton = $hasSmallButton;

        $this->user = $user;
        $this->userId = $this->user->id;
        $this->name = $this->user->name;
        $this->email = $this->user->email;
        $this->password = '';

        // initialize roleId property
        if (isset($this->user->role)) {
            $this->role = $this->user->role->id;
        } else {
            $this->role = null;
        }

        $allRoles = $roles;
        foreach ($allRoles as $role) {
            $this->rolesArray[$role->id] = $role->name;
        }
    }


    public function render()
    {
        return view('admin.livewire.user.edit');
    }


    /**
     * @throws AuthorizationException
     */
    public function updateUser()
    {
        $this->authorize('update', [User::class, $this->user]);

        // validate user input
        $this->validate();

        DB::transaction(
            function () {

                if (!isset($this->user->role)) {
                    $role = Role::where('slug', 'worker')->first();
                    // attach can only be used on m-m relation
                    // associate <-> dissociate
                    $this->user->role()->associate($role);
                } // Save new role if role is selected, and the value not equal to the current role of the user
                else {
                    if ($this->role && $this->role !== $this->user->role->id) {

                        $this->user->deleteUserRole();

                        $role = Role::where('id', $this->role)->first();
                        $this->user->role()->associate($role);
                    }
                }

                // only save password if a new one is supplied
                if ($this->password !== '') {
                    $this->userRepository->updateUser($this->user, [
                        'name' => htmlspecialchars($this->name),
                        'password' => Hash::make($this->password),
                    ]);
                } else {
                    $this->userRepository->updateUser($this->user, [
                        'name' => htmlspecialchars($this->name),
                    ]);
                }

            },
            2
        );


        $this->banner(__('Successfully updated the user ":name"!', ['name' => htmlspecialchars($this->name)]));

        return redirect()->route('user.manage');
    }

}
