<?php

namespace App\Http\Livewire\Global;

use Livewire\Component;

class Banner extends Component
{
    public bool $show;
    public string $style;
    public string $message;

    protected $listeners = [
        'onAlert'
    ];


    public function mount()
    {
        $this->show = false;
        $this->style   = '';
        $this->message = '';
    }

    public function render()
    {
        return view('admin.livewire.global.banner');
    }


    public function onAlert(array $args) {
        $this->show = true;
        $this->emitSelf('$refresh');
        $this->style = $args['style'];
        $this->message = $args['message'];
    }
}
