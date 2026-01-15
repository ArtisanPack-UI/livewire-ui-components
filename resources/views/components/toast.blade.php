<div>
    @persist('artisanpack-toaster')
    <div
        x-cloak
        x-data="{
            show: false,
            timer: '',
            toast: '',
            defaultDuration: {{ $duration }}
        }"
        @artisanpack-toast.window="
                        clearTimeout(timer);
                        toast = $event.detail.toast;
                        setTimeout(() => show = true, 100);

                        // Use event duration if provided (is not null), otherwise use the component's default duration.
                        let an_duration = toast.duration !== null ? toast.duration : defaultDuration;

                        // Only set a timer to hide if the final duration is greater than 0.
                        if (an_duration > 0) {
                            timer = setTimeout(() => show = false, an_duration);
                        }
                        "
    >
        <div
            role="alert"
            aria-atomic="true"
            class="toast !whitespace-normal rounded-md fixed cursor-pointer z-[999]"
            :class="toast.position || '{{ $position }}'"
            x-show="show"
            x-classes="alert alert-success alert-warning alert-error alert-info top-10 end-10 toast toast-top toast-bottom toast-center toast-end toast-middle toast-start"
            @click="show = false"
        >
            <div
                @php
                    $colorClasses = $getColorClasses();
                    $baseClasses = ['alert', 'gap-2'];

                    // Handle new color system
                    if (!empty($colorClasses)) {
                        // Add color classes from new system
                        foreach ($colorClasses as $type => $class) {
                            if ($type === 'style' && $class) {
                                // Handle inline styles for hex colors - will be merged below
                                $toastStyle = $class;
                            } elseif ($type !== 'style' && $class) {
                                $baseClasses[] = $class;
                            }
                        }
                    }

                    $baseClassString = implode(' ', $baseClasses);
                @endphp
                class="{{ $baseClassString }}"
                @if(isset($toastStyle)) style="{{ $toastStyle }}" @endif
                :class="toast.css"
            >
                <div x-html="toast.icon" class="hidden sm:inline-block"></div>
                <div class="grid">
                    <div x-html="toast.title" class="font-bold"></div>
                    <div x-html="toast.description" class="text-xs"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.toast = function(payload){
            window.dispatchEvent(new CustomEvent('artisanpack-toast', {detail: payload}))
        }

        /**
         * Handle toast display from failed Livewire requests.
         * Supports both Livewire 3 and Livewire 4 hook systems.
         *
         * @since 2.0.0
         * @link https://livewire.laravel.com/docs/4.x/upgrading
         */
        document.addEventListener('livewire:init', () => {
            /**
             * Process response content and display toast if present.
             *
             * @param {string} content - The response body content to parse
             * @param {function} preventDefault - Function to prevent default error handling
             */
            const processToastResponse = (content, preventDefault) => {
                try {
                    let result = JSON.parse(content);

                    if (result?.toast && typeof window.toast === "function") {
                        window.toast(result);
                    }

                    if ((result?.prevent_default ?? false) === true && typeof preventDefault === 'function') {
                        preventDefault();
                    }
                } catch (e) {
                    // Silently fail if response is not valid JSON
                }
            };

            /**
             * Feature detection for Livewire version.
             * Livewire 4 uses interceptRequest(), Livewire 3 uses hook('request', ...).
             */
            const hasInterceptRequest = typeof Livewire.interceptRequest === 'function';

            if (hasInterceptRequest) {
                // Livewire 4: Use interceptRequest with onError callback
                Livewire.interceptRequest(({ onError }) => {
                    onError(({ response, preventDefault }) => {
                        // Clone response to read body without consuming it
                        response.clone().text().then(responseBody => {
                            processToastResponse(responseBody, preventDefault);
                        }).catch(() => {
                            // Silently fail if we can't read response body
                        });
                    })
                })
            } else {
                // Livewire 3: Use hook('request', ...) with fail callback
                Livewire.hook('request', ({fail}) => {
                    fail(({status, content, preventDefault}) => {
                        processToastResponse(content, preventDefault);
                    })
                })
            }
        })
    </script>
    @endpersist
</div>