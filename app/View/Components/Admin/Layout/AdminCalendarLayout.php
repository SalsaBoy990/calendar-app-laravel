<?php

namespace App\View\Components\Admin\Layout;

use Illuminate\View\Component;

class AdminCalendarLayout extends Component
{
    /**
     * Get the view / contents that represents the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('admin.layouts.admin-calendar');
    }
}
