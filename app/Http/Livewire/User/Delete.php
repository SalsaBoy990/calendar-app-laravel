<?php

namespace App\Http\Livewire\User;

use App\Models\User;
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
    public int $userId;
    private User $user;
    public string $name;


    protected array $rules = [
        'userId' => 'required|int|min:1',
    ];

    public function mount(string $modalId, $user, bool $hasSmallButton = false)
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
        return view('livewire.user.delete');
    }


    public function deleteUser()
    {
        $user = User::findOrFail($this->userId);

        $this->authorize('delete', [User::class, $user]);

        // validate user input
        $this->validate();

        // save category, rollback transaction if fails
        DB::transaction(
            function () use ($user) {
                $user->delete();
            },
            2
        );


        $this->banner(__('The user with the name ":name" was successfully deleted.',
            ['name' => htmlspecialchars($this->name)]));
        return redirect()->route('user.manage');
    }
}
