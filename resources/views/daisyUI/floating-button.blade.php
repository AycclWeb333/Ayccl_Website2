@if(($floatingButtons[0]->active ?? false) || ($floatingButtons[1]->active ?? false))
<div class="fixed bottom-6 end-6 z-999">
    <div id="main-fab" class="transition-all duration-300 ease-in-out opacity-0 pointer-events-none">

        {{-- WhatsApp Button --}}
        @if($floatingButtons[1]->active ?? false)
        <a id="whatsapp"
           href="https://wa.me/{{ $floatingButtons[1]->mediaOne->link ?? $floatingButtons[1]->postDetailOne->content }}"
           target="_blank" aria-label="Whatsapp"
           class="chaty-tooltip hidden relative">
            <img width="50" src="{{ asset($floatingButtons[1]->mediaOne->filepath) }}"
                 alt="{{ $floatingButtons[1]->mediaOne->alt }}">
            <span id="tooltip-whatsapp"
                  class="absolute bottom-4/12 end-full px-2 py-1 bg-gray-800 text-white text-xs rounded-md whitespace-nowrap
                         opacity-0 -translate-x-4 transition-all duration-500 ease-in-out pointer-events-none">
                {{ __('adminlte::landingpage.contactus') }}
            </span>
        </a>
        @endif

        {{-- Phone Button --}}
        @if($floatingButtons[0]->active ?? false)
        <a id="phone"
           href="tel:{{ $floatingButtons[0]->mediaOne->link ?? $floatingButtons[0]->postDetailOne->content }}"
           target="_blank" aria-label="Phone"
           class="chaty-tooltip hidden relative">
            <img width="50" src="{{ asset($floatingButtons[0]->mediaOne->filepath) }}"
                 alt="{{ $floatingButtons[0]->mediaOne->alt }}">
            <span id="tooltip-phone"
                  class="absolute bottom-4/12 end-full px-2 py-1 bg-gray-800 text-white text-xs rounded-md whitespace-nowrap
                         opacity-0 -translate-x-4 transition-all duration-500 ease-in-out pointer-events-none">
                          {{ __('adminlte::landingpage.callUS') }}
            </span>
        </a>
        @endif
    </div>
</div>
@endif




<script>
    document.addEventListener('DOMContentLoaded', () => {
        const whatsappIcon = document.getElementById('whatsapp');
        const phoneIcon = document.getElementById('phone');
        const tooltipWhatsapp = document.getElementById('tooltip-whatsapp');
        const tooltipPhone = document.getElementById('tooltip-phone');
    
        const hasWhatsapp = !!whatsappIcon;
        const hasPhone = !!phoneIcon;
        let showingWhatsapp = true;
    
        // Helper functions
        const show = (el) => el && el.classList.remove('hidden');
        const hide = (el) => el && el.classList.add('hidden');
    
        const showTooltip = (el) => {
            if (!el) return;
            el.classList.remove('opacity-0', '-translate-x-4', 'pointer-events-none');
            el.classList.add('opacity-100', 'translate-x-0');
        };
        const hideTooltip = (el) => {
            if (!el) return;
            el.classList.add('opacity-0', '-translate-x-4', 'pointer-events-none');
            el.classList.remove('opacity-100', 'translate-x-0');
        };
    
        // Initial visibility
        if (hasWhatsapp && hasPhone) {
            show(whatsappIcon);
            hide(phoneIcon);
        } else if (hasWhatsapp) {
            show(whatsappIcon);
        } else if (hasPhone) {
            show(phoneIcon);
        }
    
        // Tooltip toggle every 2.5 seconds
        setInterval(() => {
            if (hasWhatsapp && showingWhatsapp) {
                tooltipWhatsapp && tooltipWhatsapp.classList.contains('opacity-0')
                    ? showTooltip(tooltipWhatsapp)
                    : hideTooltip(tooltipWhatsapp);
            }
            if (hasPhone && !showingWhatsapp) {
                tooltipPhone && tooltipPhone.classList.contains('opacity-0')
                    ? showTooltip(tooltipPhone)
                    : hideTooltip(tooltipPhone);
            }
            if (hasPhone ) {
                tooltipPhone && tooltipPhone.classList.contains('opacity-0')
                    ? showTooltip(tooltipPhone)
                    : hideTooltip(tooltipPhone);
            }
            if (!showingWhatsapp) {
                tooltipPhone && tooltipPhone.classList.contains('opacity-0')
                    ? showTooltip(tooltipPhone)
                    : hideTooltip(tooltipPhone);
            }
        }, 2500);
    
        // Switch button every 6 seconds (if both active)
        if (hasWhatsapp && hasPhone) {
            setInterval(() => {
                if (showingWhatsapp) {
                    hide(whatsappIcon);
                    hideTooltip(tooltipWhatsapp);
                    show(phoneIcon);
                } else {
                    hide(phoneIcon);
                    hideTooltip(tooltipPhone);
                    show(whatsappIcon);
                }
                showingWhatsapp = !showingWhatsapp;
            }, 6000);
        }
    });
    </script>
    