<?php

namespace App\View\Components\Public\Head;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Meta extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $title,
        public string $excerpt,
        public ?string $coverImage,
        public string $slug = '',

    ) {
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('public.components.head.meta');
    }
}
