<?php

namespace App\View\Components\Public;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Share extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public string $className, public bool $titleEnabled, public string $title = '')
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('public.components.share');
    }
}
