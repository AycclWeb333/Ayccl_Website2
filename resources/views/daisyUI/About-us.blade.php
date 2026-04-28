<section class="relative pt-2 pb-12 bg-white">
    <div class="max-w-7xl mx-auto px-6 lg:px-12 relative z-10">
        
        {{-- الجزء العلوي: النص والزر --}}
        <div class="flex flex-col {{ app()->getLocale() == 'ar' ? 'items-start text-right' : 'items-end text-left' }} mb-6 w-full" data-aos="fade-up">
            <h2 class="text-2xl md:text-4xl font-bold text-brand-green mb-6 w-full">
                {{ app()->getLocale() == 'ar' ? 'من قلب حضرموت.. انطلقنا..' : 'From the Heart of Hadhramaut.. We Started..' }}
            </h2>
            
            <p class="text-lg md:text-xl text-gray-700 leading-relaxed max-w-5xl mb-8 w-full ">
                {{ app()->getLocale() == 'ar' 
                    ? 'صرح صناعي رائد في قلب حضرموت بتكنولوجيا ألمانية ونظام الروبوت، ننتج 1.5 مليون طن سنوياً من أجود أنواع الأسمنت لدعم الاقتصاد الوطني والبنية التحتية' 
                    : 'A leading industrial edifice in the heart of Hadhramaut with German technology and a robot system, producing 1.5 million tons of the finest types of cement annually to support the national economy and infrastructure.' }}
            </p>

            <a href="{{ localizedRoute('aboutus') }}" 
               class="inline-flex items-center bg-brand-green text-white rtl:pr-12 rtl:pl-2.5 ltr:pl-12 ltr:pr-2.5 rounded-none text-xl hover:bg-black transition-all duration-500 shadow-xl hover:shadow-2xl hover:-translate-y-1 group mb-3 special_font_nasser">
                <span>{{ app()->getLocale() == 'ar' ? 'اقرأ قصتنا كاملة' : 'Read Our Full Story' }}</span>
                <div class="bg-white/20 p-2 rounded-full group-hover:bg-white/30 transition-colors ms-4 me-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 {{ app()->getLocale() == 'ar' ? 'rotate-180' : '' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </div>
            </a>
        </div>

        {{-- الخط الفاصل --}}
        <hr class="border-gray-100 mb-8">

        {{-- الجزء السفلي: الإحصائيات --}}
        <div class="text-center" data-aos="fade-up">
            <h3 class="text-2xl md:text-3xl font-bold text-brand-green mb-12 special_font_nasser" style="margin-bottom: 17px; font-size: 1.7rem;  text-transform: uppercase; letter-spacing: 2px;">
                {{ app()->getLocale() == 'ar' ? 'الأرقام تتحدث' : 'The Numbers Speak' }}
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-8 special_font_nasser">
                @foreach($statistics as $stat)
                    <div class="flex flex-col items-center">
                        <div class="flex items-baseline gap-1 mb-2">
                            <span class="stat-counter text-3xl md:text-4xl font-extrabold text-brand-green" data-value="{{ $stat->number }}">0</span>
                            <span class="text-lg md:text-xl font-bold text-gray-900">{{ app()->getLocale() == 'ar' ? $stat->unit_ar : $stat->unit_en }}</span>
                        </div>
                        <p class="text-base md:text-lg text-gray-700 font-medium">
                            {!! nl2br(e(app()->getLocale() == 'ar' ? $stat->description_ar : $stat->description_en)) !!}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<script>
    if (typeof countUpFunctionAboutUs === 'undefined') {
        const countUpFunctionAboutUs = (counter) => {
            const valueStr = counter.getAttribute('data-value');
            const value = parseFloat(valueStr);
            const isFloat = valueStr.includes('.');
            let current = 0;
            const duration = 2000;
            const interval = 20;
            const steps = duration / interval;
            const stepValue = value / steps;

            const timer = setInterval(() => {
                current += stepValue;
                if (current >= value) {
                    counter.textContent = isFloat ? value.toFixed(1) : Math.floor(value);
                    clearInterval(timer);
                } else {
                    counter.textContent = isFloat ? current.toFixed(1) : Math.floor(current);
                }
            }, interval);
        };

        const counterObserverAboutUs = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    countUpFunctionAboutUs(entry.target);
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.3 });

        document.querySelectorAll('.stat-counter').forEach(counter => counterObserverAboutUs.observe(counter));
    }
</script>
