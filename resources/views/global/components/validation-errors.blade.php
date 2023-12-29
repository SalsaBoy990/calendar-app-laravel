@if ($errors->any())
    <div class="margin-top-0-5" {{ $attributes }}>
        <div class="fs-14 bold red round padding-left-right-1 padding-top-bottom-0-5"
             style="width: fit-content"
        >{{ __('Whoops! Something went wrong.') }}</div>

        <ul class="margin-top-0-5">
            @foreach ($errors->all() as $error)
                <li class="text-red">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
