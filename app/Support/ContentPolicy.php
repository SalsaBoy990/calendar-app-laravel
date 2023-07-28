<?php
namespace App\Support;

use Spatie\Csp\Directive;
use Spatie\Csp\Keyword;
use Spatie\Csp\Policies\Basic;
use Spatie\Csp\Scheme;

class ContentPolicy extends Basic
{
    public function configure()
    {
        parent::configure();

        $this->addDirective(Directive::SCRIPT, [Keyword::SELF, Keyword::UNSAFE_INLINE]);
        $this->addDirective(Directive::STYLE, [Keyword::SELF, Keyword::UNSAFE_INLINE]);

        if (app()->environment() === 'local') {
            $this->addDirective(Directive::CONNECT, 'ws://127.0.0.1:5173');
            $this->addDirective(Directive::IMG, 'http://127.0.0.1:5173');
            $this->addDirective(Directive::SCRIPT, 'http://127.0.0.1:5173');
            $this->addDirective(Directive::STYLE, 'http://127.0.0.1:5173');
            $this->addDirective(Directive::FONT, ['https://fonts.bunny.net', 'http://localhost:8000']);
        }
        $this->addDirective(Directive::STYLE, ['https://fonts.bunny.net', 'https://static.addtoany.com', 'https://unpkg.com/leaflet@1.9.4/']);
        $this->addDirective(Directive::FONT, ['self', 'https://fonts.bunny.net', 'admin.szlavicleaningteam.hu']);
        $this->addDirective(Directive::SCRIPT, ['unsafe-eval']);
        $this->addDirective(Directive::SCRIPT, ['unsafe-inline', 'https://static.addtoany.com', 'https://unpkg.com/leaflet@1.9.4/']);
        $this->addDirective(Directive::IMG, [ '*.openstreetmap.org', 'https://unpkg.com/leaflet@1.9.4/', 'https://static.addtoany.com']);
        $this->addDirective(Directive::FRAME, ['self', 'www.google.com', 'www.youtube.com']);
        $this->addDirective(Directive::FRAME_ANCESTORS, [ 'self']);
        $this->addDirective(Directive::DEFAULT, [ 'self']);

    }

}
