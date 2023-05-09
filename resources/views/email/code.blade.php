<x-mail::message>
# {{ $title }}

Your code is : **{{ $code }}**

<x-mail::button :url="''">
Button Text
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
