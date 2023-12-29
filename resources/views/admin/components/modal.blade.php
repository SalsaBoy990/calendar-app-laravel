<div x-show="modal" x-cloak class="modal" :class="{'show': modal}">
    <div class="modal-content content-600 card card-4 animate-top relative" x-trap="modal">
        <div class="box primary round-top">
                        <span @click="closeModal()"
                              class="close-button fs-18 primary topright round-top-right text-white">
                            <i class="fa fa-times" aria-hidden="true"></i>
                        </span>
            <h3 class="text-white h4">{{ $title }}</h3>
        </div>
        <div class="box white padding-bottom-2">
            {{ $slot }}
        </div>
    </div>
</div>






