<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\InteractsWithBanner;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;


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
        return view('admin.pages.dashboard');
    }
}
