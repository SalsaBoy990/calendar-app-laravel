<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class CalendarController extends Controller {

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $permissions = auth()->user()->permissions()->get();

        return view('admin.calendar')->with([
            'permissions' => $permissions,
        ]);

    }
}
