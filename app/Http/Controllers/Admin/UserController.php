<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Interface\Repository\UserRepositoryInterface;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Support\InteractsWithBanner;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    use InteractsWithBanner;

    /**
     * @var UserRepositoryInterface
     */
    private UserRepositoryInterface $userRepository;


    /**
     * @param  UserRepositoryInterface  $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }


    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     * @throws AuthorizationException
     */
    public function index(): Factory|View|Application
    {
        $this->authorize('viewAny', User::class);

        $users = $this->userRepository->getPaginatedUsersWithRoles();

        $permissions = Permission::all();
        $roles = Role::all();

        return view('admin.pages.user.manage')->with([
            'users' => $users,
            'permissions' => $permissions,
            'roles' => $roles,
        ]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     *
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', User::class);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string'],
            'role' => ['required', 'numeric', 'min:1', 'max:2'],
        ]);

        $this->userRepository->createUser([
            'name' => htmlspecialchars($request->name),
            'email' => htmlspecialchars($request->email),
            'password' => Hash::make($request->password),
            'role' => intval($request->role), // 1 = admin, 2 = client
            'remember_token' => Str::random(10),
        ]);

        $this->banner(__('Successfully created the user with the name of ":name"!',
            [htmlspecialchars($request->name)]));
        return redirect()->route('user.index');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  User  $user
     *
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function destroy(User $user): RedirectResponse
    {
        $this->authorize('delete', [User::class, $user]);

        $oldName = htmlentities($user->name);
        $user->delete();

        $this->banner(__('Successfully deleted the user with the name of ":name"!', [$oldName]));
        return redirect()->route('user.manage');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  Request  $request
     * @param  User  $user
     *
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function deleteAccount(Request $request, User $user): RedirectResponse
    {
        $this->authorize('delete', [User::class, $user]);
        $oldName = htmlentities($user->name);

        if (Hash::check($request->input('password'), $user->password)) {
            $this->userRepository->deleteUser($user);

            $this->banner(__('Successfully deleted the user with the name of ":name"!', [$oldName]));
            return redirect()->route('login');

        } else {
            $this->banner(__('Incorrect password. Try again.'), 'danger');
            return redirect()->route('user.account', $user->id);
        }
    }


    /**
     * Show user account with current user data
     * @param  User  $user
     *
     * @return Factory|View|Application
     * @throws AuthorizationException
     */
    public function account(User $user): Factory|View|Application
    {
        $this->authorize('view', [User::class, $user]);

        return view('admin.pages.user.account')->with([
            'user' => $user
        ]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  User  $user
     *
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $this->authorize('update', [User::class, $user]);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'min:0'],
            'enable2fa' => ['nullable', 'boolean'],
        ]);


        if ($request->password === null) {
            $this->userRepository->updateUser($user, [
                'name' => strip_tags($request->name),
                'enable_2fa' => intval($request->enable2fa),
            ]);
        } else {
            $this->userRepository->updateUser($user, [
                'name' => $request->name,
                'password' => Hash::make($request->password),
                'enable_2fa' => intval($request->enable2fa),
            ]);
        }

        $this->banner(__('Successfully updated your account!'));
        return redirect()->route('user.account', $user->id);
    }
}
