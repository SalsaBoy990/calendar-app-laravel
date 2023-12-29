<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
//        $user = User::findOrFail(1);
//        dd($user->can('manage-workers'));
//        dd($user->hasRole('administrator')); // will return true
//        dd($user->hasRole('project-manager'));// will return false
//        $user->givePermissionsTo( ['manage-workers', 'create-tasks']);
//        dd($user->hasPermissionTo(null, 'create-tasks'));// will return true
//        dd($user->getAllPermissions(['manage-workers']));

//        $permissions = $user->permissions()->get();
        $permissions = auth()->user()->permissions()->get();

//        dd($permissions);

        return view('home')->with([
            'permissions' => $permissions,
        ]);

    }
}
