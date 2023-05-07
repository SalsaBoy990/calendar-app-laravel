<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Support\InteractsWithBanner;

class UserController extends Controller
{
    use InteractsWithBanner;

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     * @throws AuthorizationException
     */
    public function index()
    {
//        $this->authorize('viewAny', User::class);

        $users = User::orderBy('created_at', 'DESC')->get();
        $permissions = Permission::all();
        $roles = Role::all();

        return view('admin.user.manage')->with([
            'users' => $users,
            'permissions' => $permissions,
            'roles' => $roles,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', User::class);

//        return view('admin.user.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     *
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function store(Request $request): RedirectResponse {

        $this->authorize('create', User::class);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string'],
            'role' => ['required', 'numeric', 'min:1', 'max:2'],
        ]);

        $newUser = User::create([
            'name' => htmlspecialchars($request->name),
            'email' => htmlspecialchars($request->email),
            'password' => Hash::make($request->password),
            'role' => intval($request->role), // 1 = admin, 2 = client
            'remember_token' => Str::random(10),
        ]);

        $newUser->save();

        $this->banner('Successfully created the with the name of "' . htmlspecialchars($request->name) . '"!');
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
    public function destroy(User $user): RedirectResponse {
        $this->authorize('delete', [User::class, $user]);

        $oldName = htmlentities($user->name);
        $user->delete();

        $this->banner('Successfully deleted the user with the name of "' . $oldName . '"!');
        return redirect()->route('user.index');
    }
}
