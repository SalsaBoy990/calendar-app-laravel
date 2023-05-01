<x-admin-nosidebar-layout>

    @section('content')
        <main class="padding-1">
            <a href="{{ route('dashboard')}}"
               class="button alt bg-white">{{ __('Back') }}</a>
            <p class="margin-bottom-0">{{ __('Edit entity') }}</p>
            <h1 class="margin-0 h2">
                {{ __('Entity name') }}
            </h1>

            <form action="{{ //route('category.update', $category->id) }}"
                  method="POST"
                  enctype="application/x-www-form-urlencoded"
                  accept-charset="UTF-8"
                  autocomplete="off"
            >
                @method("PUT")
                @csrf

                <x-admin.validation-errors/>

                <div>
                    <button type="submit" class="primary">{{ __("Update") }}
                    </button>

                    <a href="{{ route('dashboard')}}"
                       class="button alt bg-white">{{ __('Cancel') }}</a>
                </div>
            </form>
        </main>

    @endsection

</x-admin-nosidebar-layout>
