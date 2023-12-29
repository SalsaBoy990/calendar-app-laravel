@if ($titleEnabled === true)
    <h4 class="margin-bottom-0-5">{{ __('Share:') }}</h4>
@endif

@php
    $title = urlencode($title !== '' ? $title : config('app.name'));
    $url = urlencode(url()->current());
@endphp

    <!-- AddToAny BEGIN -->
<div class="a2a_buttons {{ $className }}">
    <a class="a2a_button_facebook"
       title="{{ __('Share on Facebook') }}"
       href="https://www.addtoany.com/add_to/facebook?linkurl={{ $url }}&amp;linkname={{ $title }}"
       target="_blank"><img src="https://static.addtoany.com/buttons/facebook.svg" alt="Facebook" width="32" height="32"></a>

    <a class="a2a_button_whatsapp"
       title="{{ __('Share on Whatsapp') }}"
       href="https://www.addtoany.com/add_to/whatsapp?linkurl={{ $url }}&amp;linkname={{ $title }}"
       target="_blank"><img src="https://static.addtoany.com/buttons/whatsapp.svg" alt="Whatsapp" width="32" height="32"></a>

    <a class="a2a_button_facebook_messenger"
       title="{{ __('Share on Messenger') }}"
       href="https://www.addtoany.com/add_to/facebook_messenger?linkurl={{ $url }}&amp;linkname={{ $title }}"
       target="_blank"><img src="https://static.addtoany.com/buttons/facebook_messenger.svg" alt="Messenger" width="32" height="32"></a>

    <a class="a2a_button_telegram"
       title="{{ __('Share on Telegram') }}"
       href="https://www.addtoany.com/add_to/telegram?linkurl={{ $url }}&amp;linkname={{ $title }}"
       target="_blank"><img src="https://static.addtoany.com/buttons/telegram.svg" alt="Telegram" width="32" height="32"></a>

    <a class="a2a_button_email"
       title="{{ __('Send in email') }}"
       href="https://www.addtoany.com/add_to/email?linkurl={{ $url }}&amp;linkname={{ $title }}"
       target="_blank"><img src="https://static.addtoany.com/buttons/email.svg" alt="Email" width="32" height="32"></a>

    <a class="a2a_button_print"
       title="{{ __('Print it') }}"
       href="https://www.addtoany.com/add_to/print?linkurl={{ $url }}&amp;linkname={{ $title }}"
       target="_blank"><img src="https://static.addtoany.com/buttons/print.svg" alt="Print" width="32" height="32"></a>
</div>
<!-- AddToAny END -->
