<div x-data="{
    panelOpen: $wire.entangle('panelOpen'),
    count: $wire.entangle('count')
}"
>
    <section class="banner">
        <h1 class="h3">{{ __('Section Banner') }}</h1>
        <p>{{ __('This is a description of this section') }}</p>
        <div class="searchbar relative">
            <form wire:submit.prevent="searchCategory">

                <input type="search"
                       wire:model.defer="searchTerm"
                       type="text"
{{--                       class="{{ $errors->has('searchTerm') ? 'red' : '' }}"--}}
                       name="searchTerm"
                       value=""
                >
                <button type="submit">
                    <span wire:loading wire:target="searchCategory" class="animate-spin">&#9696;</span>
                    <span wire:loading.remove wire:target="searchCategory">{{ __('Search') }}</span>
                </button>
            </form>
        </div>
    </section>

    @php
    $resultsLabel = __('Results');
    @endphp

    <div x-cloak class="search-results relative">
        <div x-show="panelOpen == true"
             :class="{'show': panelOpen == true }"
             class="card white absolute padding-1 z-2 hide"
        >
            <span @click="panelOpen = false"
                  class="close-button large topright round-large-top-right">&times;</span>
            <h2 class="fs-16 margin-top-0" x-text="'{{ $resultsLabel }} (' + count + ')'"></h2>
            <div id="search-results">

                @if(isset($results))
                    @foreach($results as $item)

                        <div class="box round border border-default">
                            <b class="h4">
                                <a href="{{ route('category.selected', $item->id)}}">{{ $item->name }}</a>
                            </b>
                            <p class="fs-14">{!! substr($item->content, 0, 120) . '...' !!} </p>
                        </div>
                    @endforeach
                @endif
            </div>

        </div>
    </div>
</div>


