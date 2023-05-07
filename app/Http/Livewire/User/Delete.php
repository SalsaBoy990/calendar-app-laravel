<?php

namespace App\Http\Livewire\User;

use App\Models\User;
use App\Support\InteractsWithBanner;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Delete extends Component
{
    use InteractsWithBanner;

    // used by blade / alpinejs
    public string $modalId;
    public bool $isModalOpen;
    public bool $hasSmallButton;

    // inputs
    public int $userId;
    public string $name;


    protected array $rules = [
        'userId' => 'required|int|min:1',
    ];

    public function mount(string $modalId, $user, bool $hasSmallButton = false)
    {
        $this->modalId = $modalId;
        $this->isModalOpen = false;
        $this->hasSmallButton = $hasSmallButton;
        $this->userId = $user->id;
        $this->name = $user->name;
    }


    public function render()
    {
        return view('livewire.user.delete');
    }


    public function deleteUser()
    {
        // validate user input
        $this->validate();

        // save category, rollback transaction if fails
        DB::transaction(
            function () {
                $user = User::findOrFail($this->userId);
                $user->delete();
            },
            2
        );


        $this->banner('The user with the name "' . $this->name .  '" was successfully deleted.');
        return redirect()->route('user.manage');
    }
}
