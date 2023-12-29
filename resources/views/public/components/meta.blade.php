    <!-- Metas for social media-->
    <meta name="description" content="{{ $excerpt }}"/>
    <meta property="og:title" content="{{ $title }} | {{ config('app.name') }}"/>
    <meta property="og:url" content="{{ config('app.url') . '/' . $slug }}"/>
    <meta property="og:site_name" content="{{ config('app.name') }}"/>
    <meta property="og:description" content="{{ $excerpt }}"/>

@if (isset($coverImage))
    <meta property="og:image" content="{{ asset($coverImage) }}"/>
@else
    <meta property="og:image" content="{{ asset('/storage/photos/1/maura_mazambi.jpg') }}"/>
@endif
    <meta property="og:type" content="website"/>

    <!-- Twitter -->
    <meta name="twitter:card" content="summary"/>
    <meta name="twitter:site" content="{{ config('app.name') }}"/>
    <meta name="twitter:creator" content="{{ config('app.name') }}"/>
    <meta name="twitter:title" content="{{ $title }} | {{ config('app.name') }}"/>
    <meta name="twitter:description" content="{{ $excerpt }}"/>
    <meta name="twitter:url" content="{{ config('app.url') . '/' . $slug }}"/>
@if (isset($coverImage))
    <meta property="twitter:image" content="{{ asset($coverImage) }}"/>
@else
    <meta property="twitter:image" content="{{ asset('/storage/photos/1/maura_mazambi.jpg') }}"/>
@endif
