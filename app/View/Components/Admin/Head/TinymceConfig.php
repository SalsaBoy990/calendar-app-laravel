<?php

namespace App\View\Components\Admin\Head;

use Illuminate\View\Component;

class TinymceConfig extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('admin.components.head.tinymce-config');
    }
}
