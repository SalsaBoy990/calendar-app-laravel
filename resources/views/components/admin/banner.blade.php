@props(['style' => session('flash.bannerStyle', 'success'), 'message' => session('flash.banner')])

<div x-data="{{ json_encode(['show' => true, 'style' => $style, 'message' => $message]) }}"
     class="panel notification padding-top-bottom-0"
     :class="{ 'success': style == 'success', 'danger': style == 'danger', 'info': style != 'success' && style != 'danger' }"
     style="display: none;"
     x-show="show && message"
     x-init="
                document.addEventListener('banner-message', event => {
                    style = event.detail.style;
                    message = event.detail.message;
                    show = true;
                });
            ">

    <div class="notification-outer">
        <div class="notification-inner">
                <span :class="{ 'success': style == 'success', 'danger': style == 'danger' }" aria-label="{{ __('System response notification') }}">
                    <i x-show="style == 'success'" class="fa fa-check" aria-hidden="true"></i>
                    <i x-show="style == 'danger'" class="fa fa-exclamation-triangle" aria-hidden="true"></i>
                    <i x-show="style != 'success' && style != 'danger'" class="fa fa-info-circle"
                       aria-hidden="true"></i>
                </span>

            <p x-text="message" class="message"></p>
        </div>

        <div>
            <button
                type="button"
                class=""
                :class="{ 'success': style == 'success', 'danger': style == 'danger' }"
                aria-label="Dismiss system notification"
                x-on:click="show = false"
            >
                <i class="fa fa-times" aria-hidden="true"></i>
            </button>
        </div>
    </div>

</div>
