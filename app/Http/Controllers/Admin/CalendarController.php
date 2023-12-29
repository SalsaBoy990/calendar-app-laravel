<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class CalendarController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('admin.pages.calendar');
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function workers()
    {
        return view('admin.pages.workers')->with([
        ]);

    }
}
