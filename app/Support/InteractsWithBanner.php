<?php

namespace App\Support;

trait InteractsWithBanner
{
    public function banner(string $message, string $style = 'success')
    {
        request()->session()->flash('flash.banner', $message);
        request()->session()->flash('flash.bannerStyle', $style);
    }
}
