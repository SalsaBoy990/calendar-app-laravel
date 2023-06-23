<?php

namespace App\Http\Controllers;

class CalendarController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('admin.calendar');
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function workers()
    {
        return view('admin.workers')->with([
        ]);

    }
}
