@props(['for'])

@error($for)
<p {{ $attributes->merge(['class' => 'fs-14 error-message']) }}>{{ $message }}</p>
@enderror
