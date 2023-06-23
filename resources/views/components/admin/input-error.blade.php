@props(['for'])

@error($for)
<p {{ $attributes->merge(['class' => 'fs-14 darkred']) }}>{{ $message }}</p>
@enderror
