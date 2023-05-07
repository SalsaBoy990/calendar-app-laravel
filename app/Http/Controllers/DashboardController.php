<?php

namespace App\Http\Controllers;

use App\Support\InteractsWithBanner;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;


class DashboardController extends Controller
{
    use InteractsWithBanner;

    /**
     * Admin dashboard page
     *
     * @return Application|Factory|View
     */
    public function index(): Application|Factory|View
    {
        $permissions = auth()->user()->permissions()->get();

        return view('admin.dashboard')->with([
//            'permissions' => $permissions,
        ]);
    }
}
