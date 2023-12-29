<div x-data="{ 'show': $wire.entangle('show'), 'style': $wire.entangle('style'), 'message': $wire.entangle('message') }"
     class="alert notification padding-top-bottom-0"
     :class="{ 'success': style === 'success', 'danger': style === 'danger', 'info': style !== 'success' && style !== 'danger' }"
     x-show="show === true"
     x-effect="show === true && window.scrollTo({ top: 0, behavior: 'smooth'})"
     x-cloak
>

    <div class="notification-outer">
        <div class="notification-inner">
                <span :class="{ 'success': style === 'success', 'danger': style === 'danger' }"
                      aria-label="{{ __('System notification') }}">
                    <i x-show="style === 'success'" class="fa fa-check" aria-hidden="true"></i>
                    <i x-show="style === 'danger'" class="fa fa-exclamation-triangle" aria-hidden="true"></i>
                    <i x-show="style !== 'success' && style !== 'danger'" class="fa fa-info-circle"
                       aria-hidden="true"></i>
                </span>

            <p x-text="message" class="message"></p>
        </div>

        <div>
            <button
                type="button"
                class=""
                :class="{ 'success': style === 'success', 'danger': style === 'danger' }"
                aria-label="{{  __('Dismiss alert') }}"
                x-on:click="show = false"
            >
                <i class="fa fa-times" aria-hidden="true"></i>
            </button>
        </div>
    </div>

</div>
