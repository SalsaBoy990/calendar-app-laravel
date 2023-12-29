@if ($errors->any())
    <div {{ $attributes }}>
        <div class="fs-14 bold red">{{ __('Whoops! Something went wrong.') }}</div>

        <ul class="white">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
