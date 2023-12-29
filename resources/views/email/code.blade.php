<x-mail::message>
# {{ $title }}

{{ __('Your login code is: ') }}

**{{ $code }}**



{{ __('Greetings,') }}

{{ config('app.name') }}
</x-mail::message>
