<?php

namespace App\Http\Livewire\Admin\User;

use App\Interface\Repository\UserRepositoryInterface;
use App\Models\User;
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
    public int $userId;
    public User $user;
    public string $name;


    protected array $rules = [
        'userId' => 'required|int|min:1',
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


    public function mount(string $modalId, User $user, bool $hasSmallButton = false)
    {
        $this->modalId = $modalId;
        $this->isModalOpen = false;
        $this->hasSmallButton = $hasSmallButton;
        $this->user = $user;
        $this->userId = $user->id;
        $this->name = $user->name;
    }


    public function render()
    {
        return view('admin.livewire.user.delete');
    }


    /**
     * @throws AuthorizationException
     */
    public function deleteUser()
    {
        $this->authorize('delete', [User::class, $this->user]);

        // validate user input
        $this->validate();

        // save category, rollback transaction if fails
        DB::transaction(
            function () {
                $this->userRepository->deleteUser($this->user);
            },
            2
        );


        $this->banner(__('The user with the name ":name" was successfully deleted.',
            ['name' => htmlspecialchars($this->name)]));
        return redirect()->route('user.manage');
    }
}
