<nav class="breadcrumb {{ isset($centerAlign) ? 'breadcrumb-center' : '' }}">
    <ol>
        <li>
            <a href="{{ route('blog.index') }}">
                <i class="fa fa-home" aria-hidden="true"></i>
                {{ __('Home') }}
            </a>
        </li>
        <li>
            <i class="fa-solid fa-angle-right"></i>
        </li>
        <li>{{ $title }}</li>
    </ol>
</nav>
