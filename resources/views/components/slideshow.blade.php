{{-- @props(['slideshows'])

<section
    class="relative group flex flex-col h-screen w-full justify-center items-center overflow-hidden cursor-grab select-none"
    id="slideshow-container"
>
    <!-- Slideshow Image Stack -->
    <div class="relative h-full w-full overflow-hidden">
        @foreach ($slideshows as $index => $slideshow)
            <img src="{{ asset($slideshow->image) }}"
                class="slideshow-image absolute top-0 left-0 h-full w-full object-cover rounded-medium transition-transform duration-700 ease-in-out"
                data-text="{{ $slideshow->text }}"
                style="transform: translateX({{ $loop->index === 0 ? '0%' : '100%' }}); z-index: {{ $loop->index === 0 ? '10' : '0' }};"
            />
        @endforeach
    </div>

    <!-- Gradient overlay + Text + Dots -->
    <div class="absolute inset-0 z-20">
        <div class="absolute bottom-0 left-0 w-full h-[75%] sm:h-[65%] bg-gradient-to-t from-black to-transparent rounded-medium"></div>

        <div class="absolute flex flex-col lg:flex-row inset-6 sm:inset-8 md:inset-10 lg:inset-11 justify-end lg:justify-between lg:items-end">
            <p id="slideshow-text"
               class="text-white text-[31px] sm:text-[36px] md:text-[47px] lg:text-[58px] font-medium ltr:text-left rtl:text-right leading-tight">
               {{ $slideshows[0]->text }}
            </p>

            <div class="flex gap-x-2.5 sm:gap-x-3.5 mt-3.5 sm:mt-4 lg:mt-0" id="slideshow-dots">
                @foreach ($slideshows as $index => $slideshow)
                    <div
                        class="slideshow-dot cursor-pointer w-2.5 h-2.5 sm:w-3.5 sm:h-3.5 rounded-full transition duration-[1500ms] {{ $loop->first ? 'bg-white opacity-100' : 'bg-white opacity-30' }}">
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const images = Array.from(document.querySelectorAll('.slideshow-image'));
        const dots = document.querySelectorAll('.slideshow-dot');
        const textBox = document.getElementById('slideshow-text');
        const container = document.getElementById('slideshow-container');

        let current = 0;
        const duration = 5000;
        let timer;

        function resetImages() {
            images.forEach((img, index) => {
                img.style.transition = 'none';
                img.style.transform = index === current ? 'translateX(0%)' : 'translateX(100%)';
                img.style.zIndex = index === current ? 10 : 0;
            });
        }

        function showSlide(newIndex, direction = 1) {
            if (newIndex === current) return;

            const currentImage = images[current];
            const newImage = images[newIndex];

            // Prep next image position
            newImage.style.transition = 'none';
            newImage.style.transform = `translateX(${direction * 100}%)`;
            newImage.style.zIndex = 10;

            // Animate in next frame
            requestAnimationFrame(() => {
                // Animate both
                currentImage.style.transition = 'transform 0.7s ease-in-out';
                newImage.style.transition = 'transform 0.7s ease-in-out';

                currentImage.style.transform = `translateX(${-direction * 100}%)`;
                newImage.style.transform = 'translateX(0%)';

                // After animation, reset positions
                setTimeout(() => {
                    images.forEach((img, i) => {
                        if (i !== newIndex) {
                            img.style.transition = 'none';
                            img.style.transform = 'translateX(100%)';
                            img.style.zIndex = 0;
                        }
                    });
                    newImage.style.zIndex = 10;
                    current = newIndex;
                    textBox.innerHTML = newImage.getAttribute('data-text').replace(/\n/g, '<br>');

                    // Update dots
                    dots.forEach(dot => dot.classList.replace('opacity-100', 'opacity-30'));
                    dots[current].classList.replace('opacity-30', 'opacity-100');
                }, 700);
            });
        }

        function nextSlide() {
            const prev = (current - 1 + images.length) % images.length;
            showSlide(prev, -1);
        }

        function prevSlide() {
            const next = (current + 1) % images.length;
            showSlide(next, 1);
        }

        function startTimer() {
            timer = setInterval(nextSlide, duration);
        }

        function stopTimer() {
            clearInterval(timer);
        }

        // Start everything
        resetImages();
        startTimer();

        // Dot click
        dots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                stopTimer();
                showSlide(index, index > current ? 1 : -1);
                startTimer();
            });
        });

        // Swipe/drag handling
        let startX = 0;
        let isDragging = false;

        function onDragStart(e) {
            isDragging = true;
            startX = e.type.includes('mouse') ? e.clientX : e.touches[0].clientX;
        }

        function onDragEnd(e) {
            if (!isDragging) return;
            isDragging = false;

            const endX = e.type.includes('mouse') ? e.clientX : e.changedTouches[0].clientX;
            const diff = endX - startX;

            if (Math.abs(diff) > 50) {
                stopTimer();
                diff > 0 ? prevSlide() : nextSlide();
                startTimer();
            }
        }

        container.addEventListener('mousedown', onDragStart);
        container.addEventListener('mouseup', onDragEnd);
        container.addEventListener('touchstart', onDragStart);
        container.addEventListener('touchend', onDragEnd);
    });
</script> --}}


{{-- @props(['slideshows'])

<section
    class="relative group flex flex-col h-screen w-full justify-center items-center overflow-hidden cursor-grab select-none"
    id="slideshow-container"
>
    <!-- Slideshow Image Stack -->
    <div class="relative h-full w-full overflow-hidden">
        @foreach ($slideshows as $index => $slideshow)
            <img src="{{ asset($slideshow->image) }}"
                class="slideshow-image absolute top-0 left-0 h-full w-full object-cover rounded-medium transition-transform duration-700 ease-in-out"
                data-text="{{ $slideshow->text }}"
                style="transform: translateX({{ $loop->index === 0 ? '0%' : '100%' }}); z-index: {{ $loop->index === 0 ? '10' : '0' }};"
            />
        @endforeach
    </div>

    <!-- Gradient overlay + Text + Dots -->
    <div class="absolute inset-0 z-20">
        <div class="absolute bottom-0 left-0 w-full h-[75%] sm:h-[65%] bg-gradient-to-t from-black to-transparent rounded-medium"></div>

        <div class="absolute flex flex-col lg:flex-row inset-6 sm:inset-8 md:inset-10 lg:inset-11 justify-end lg:justify-between lg:items-end">
            <p id="slideshow-text"
               class="text-white text-[31px] sm:text-[36px] md:text-[47px] lg:text-[58px] font-medium ltr:text-left rtl:text-right leading-tight">
               {{ $slideshows[0]->text }}
            </p>

            <div class="flex gap-x-2.5 sm:gap-x-3.5 mt-3.5 sm:mt-4 lg:mt-0" id="slideshow-dots">
                @foreach ($slideshows as $index => $slideshow)
                    <div
                        class="slideshow-dot cursor-pointer w-2.5 h-2.5 sm:w-3.5 sm:h-3.5 rounded-full transition duration-[1500ms] {{ $loop->first ? 'bg-white opacity-100' : 'bg-white opacity-30' }}">
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const images = Array.from(document.querySelectorAll('.slideshow-image'));
        const dots = document.querySelectorAll('.slideshow-dot');
        const textBox = document.getElementById('slideshow-text');
        const container = document.getElementById('slideshow-container');

        let current = 0;
        const duration = 5000;
        let timer;

        function resetImages() {
            images.forEach((img, index) => {
                img.style.transition = 'none';
                img.style.transform = index === current ? 'translateX(0%)' : 'translateX(100%)';
                img.style.zIndex = index === current ? 10 : 0;
            });
        }

        function showSlide(newIndex, direction = 1) {
            if (newIndex === current) return;

            const currentImage = images[current];
            const newImage = images[newIndex];

            // Prep next image position
            newImage.style.transition = 'none';
            newImage.style.transform = `translateX(${direction * 100}%)`;
            newImage.style.zIndex = 10;

            // Animate in next frame
            requestAnimationFrame(() => {
                // Animate both
                currentImage.style.transition = 'transform 0.7s ease-in-out';
                newImage.style.transition = 'transform 0.7s ease-in-out';

                currentImage.style.transform = `translateX(${-direction * 100}%)`;
                newImage.style.transform = 'translateX(0%)';

                // After animation, reset positions
                setTimeout(() => {
                    images.forEach((img, i) => {
                        if (i !== newIndex) {
                            img.style.transition = 'none';
                            img.style.transform = 'translateX(100%)';
                            img.style.zIndex = 0;
                        }
                    });
                    newImage.style.zIndex = 10;
                    current = newIndex;
                    textBox.innerHTML = newImage.getAttribute('data-text').replace(/\n/g, '<br>');

                    // Update dots
                    dots.forEach(dot => dot.classList.replace('opacity-100', 'opacity-30'));
                    dots[current].classList.replace('opacity-30', 'opacity-100');
                }, 700);
            });
        }

        function nextSlide() {
            const next = (current + 1) % images.length;
            showSlide(next, 1);
        }

        function prevSlide() {
            const prev = (current - 1 + images.length) % images.length;
            showSlide(prev, -1);
        }

        function startTimer() {
            timer = setInterval(nextSlide, duration);
        }

        function stopTimer() {
            clearInterval(timer);
        }

        // Start everything
        resetImages();
        startTimer();

        // Dot click
        dots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                stopTimer();
                showSlide(index, index > current ? 1 : -1);
                startTimer();
            });
        });

        // Swipe/drag handling
        let startX = 0;
        let isDragging = false;

        function onDragStart(e) {
            isDragging = true;
            startX = e.type.includes('mouse') ? e.clientX : e.touches[0].clientX;
        }

        function onDragEnd(e) {
            if (!isDragging) return;
            isDragging = false;

            const endX = e.type.includes('mouse') ? e.clientX : e.changedTouches[0].clientX;
            const diff = endX - startX;

            if (Math.abs(diff) > 50) {
                stopTimer();
                diff > 0 ? prevSlide() : nextSlide();
                startTimer();
            }
        }

        container.addEventListener('mousedown', onDragStart);
        container.addEventListener('mouseup', onDragEnd);
        container.addEventListener('touchstart', onDragStart);
        container.addEventListener('touchend', onDragEnd);
    });
</script> --}}


@props(['slideshows'])

{{-- <style>
    @import url('https://fonts.googleapis.com/css2?family=Changa:wght@400;700;800&display=swap');

    #hero-slider {
        font-family: 'Changa', sans-serif;
    }

    /* التأثيرات الحركية للنص */
    .slide-content-wrapper.active .slide-title {
        opacity: 1;
        transform: translateY(0);
    }
    .slide-content-wrapper.active .slide-desc {
        opacity: 1;
        transform: translateY(0);
        transition-delay: 0.3s;
    }
    .slide-content-wrapper.active .slide-btn {
        opacity: 1;
        transform: translateY(0);
        transition-delay: 0.5s;
    }

    /* تأثير الزووم الهادئ للصورة */
    .slide-img-anim {
        transition: transform 10s linear;
    }
    .active-slide .slide-img-anim {
        transform: scale(1.15);
    }

    /* اللون الأخضر الزرعي المطابق للشعار */
    .text-brand-green { color: #2D843D; } /* أخضر زرعي غامق مثل الشعار */
    .bg-brand-green { background-color: #2D843D; }
</style>

<section class="relative w-full h-screen overflow-hidden bg-black" id="hero-slider">

    <div class="relative h-full w-full">
        @php
            $slides = [
                ['img' => 'slide1.jpeg', 'w1' => 'إسمنت', 'w2' => 'حضرموت ', 'desc' => 'نضع بين أيديكم أجود أنواع الإسمنت بمواصفات عالمية لضمان ديمومة البناء.'],
                ['img' => 'slide2.png', 'w1' => 'قوة', 'w2' => 'البناء', 'desc' => 'صلابة تتحدى الزمن وتصمد أمام كافة الظروف المناخية القاسية.'],
                ['img' => 'slide3.png', 'w1' => 'دقة', 'w2' => 'المعايير', 'desc' => 'نلتزم بأعلى معايير الجودة في كل مرحلة من مراحل الإنتاج.'],
                ['img' => 'slide4.png', 'w1' => 'شريك', 'w2' => 'العمر', 'desc' => 'ساهمنا في بناء أضخم المشاريع المحلية بكفاءة لا تضاهى.'],
                ['img' => 'slide5.png', 'w1' => 'فخر', 'w2' => 'الصناعة', 'desc' => 'بأيدي يمنية وتكنولوجيا متطورة، نسابق الزمن نحو الريادة.'],
            ];
        @endphp

        @foreach($slides as $index => $slide)
        <div class="slide-item absolute inset-0 transition-opacity duration-1000 ease-in-out {{ $index == 0 ? 'opacity-100 z-20 active-slide' : 'opacity-0 z-10' }}">
            <img src="{{ asset('images/slids/' . $slide['img']) }}"
                 class="w-full h-full object-cover slide-img-anim"
                 alt="الشركة العربية للإسمنت">

            <div class="absolute inset-0 bg-black/40"></div>

            <div class="absolute inset-0 flex flex-col justify-center items-center text-center px-4">
                <div class="slide-content-wrapper max-w-5xl {{ $index == 0 ? 'active' : '' }}">
                    <h2 class="slide-title text-5xl md:text-8xl font-extrabold mb-6 opacity-0 translate-y-12 transition-all duration-700 flex flex-wrap justify-center gap-x-6 drop-shadow-2xl">
                        <span class="text-brand-green">{{ $slide['w1'] }}</span>
                        <span class="text-black">{{ $slide['w2'] }}</span>
                    </h2>

                    <p class="slide-desc text-white text-xl md:text-3xl mb-10 font-normal max-w-3xl mx-auto opacity-0 translate-y-8 transition-all duration-700 drop-shadow-md">
                        {{ $slide['desc'] }}
                    </p>

                    <div class="slide-btn opacity-0 translate-y-8 transition-all duration-700">

                            <a href="#" class="btn bg-brand-green hover:bg-white hover:text-white border-none text-black px-12 rounded-none font-bold transition-all duration-300 shadow-2xl">
                            إعرف أكثر
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="absolute bottom-12 left-1/2 -translate-x-1/2 z-50 flex items-center gap-6">
        <button onclick="prevSlide()" class="group p-3 rounded-full bg-white/10 hover:bg-brand-green border-2 border-white/30 hover:border-brand-green transition-all duration-300 backdrop-blur-sm">
            <svg class="w-8 h-8 text-white group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        </button>

        <button onclick="nextSlide()" class="group p-3 rounded-full bg-white/10 hover:bg-brand-green border-2 border-white/30 hover:border-brand-green transition-all duration-300 backdrop-blur-sm">
            <svg class="w-8 h-8 text-white group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        </button>
    </div>
</section>

<script>
    let currentSlide = 0;
    const slides = document.querySelectorAll('.slide-item');
    const contents = document.querySelectorAll('.slide-content-wrapper');

    function showSlide(n) {
        // إزالة الحالة النشطة من السلايد الحالي
        slides[currentSlide].classList.remove('opacity-100', 'z-20', 'active-slide');
        slides[currentSlide].classList.add('opacity-0', 'z-10');
        contents[currentSlide].classList.remove('active');

        currentSlide = (n + slides.length) % slides.length;

        // تفعيل السلايد الجديد
        slides[currentSlide].classList.remove('opacity-0', 'z-10');
        slides[currentSlide].classList.add('opacity-100', 'z-20', 'active-slide');

        // تفعيل أنيميشن النصوص بعد تأخير بسيط
        setTimeout(() => {
            contents[currentSlide].classList.add('active');
        }, 100);
    }

    function nextSlide() { showSlide(currentSlide + 1); }
    function prevSlide() { showSlide(currentSlide - 1); }

    // تغيير تلقائي كل 7 ثواني لإعطاء وقت لقراءة الوصف
    setInterval(nextSlide, 7000);
</script> --}}

{{-- ////////////////////////////////////////////// old  is upper/////////////////////////--}}
{{-- <style>
    @import url('https://fonts.googleapis.com/css2?family=Changa:wght@400;700;800&display=swap');

    #slideshow-container {
        font-family: 'Changa', sans-serif;
    }

    .text-brand-green { color: #2D843D; }
    .bg-brand-green { background-color: #2D843D; }

    .text-black-custom {
        color: #000000;
        text-shadow: 0px 0px 1px rgba(255,255,255,0.3);
    }

    #slideshow-text h2, #slideshow-text p, #slideshow-text div {
        animation: slideUpFade 0.8s ease forwards;
    }

    @keyframes slideUpFade {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

 .wave-container {
    position: absolute;
    bottom: -1px;
    left: 0;
    width: 100%;
    overflow: hidden;
    line-height: 0;
    z-index: 35;
}

.wave-container svg {
    position: relative;
    display: block;
    width: calc(100% + 1.3px);
    height: 100px; /* يمكنك زيادة الارتفاع لزيادة بروز التموج */
}

.fill-black { fill: #000000; }
.fill-white { fill: #ffffff; }
.fill-brand-green { fill: #2D843D; }
</style>

<section class="relative group flex flex-col mt-24 sm:mt-0 h-100 sm:h-screen w-full justify-center items-center overflow-hidden select-none"
    id="slideshow-container">

    <div class="relative h-full w-full overflow-hidden">
        @foreach ($slideshows as $slideshow )
            @php
                $fullTitle = $slideshow->postDetailOne->title;
                $words = explode(' ', trim($fullTitle));
                $firstWord = $words[0] ?? '';
                $remainingTitle = implode(' ', array_slice($words, 1));
                $link = $slideshow->mediaOne->link ?? '#';

                // هنا التعديل: أضفنا pb-40 sm:pb-64 لرفع المحتوى للأعلى
                $textContent = '
                <div class="flex flex-col items-center text-center pb-40 sm:pb-64">
                    <h2 class="text-5xl md:text-8xl font-extrabold mb-6 flex flex-wrap justify-center gap-x-6 drop-shadow-2xl leading-tight">
                        <span class="text-brand-green">'.$firstWord.'</span>
                        <span class="text-black-custom">'.$remainingTitle.'</span>
                    </h2>

                    <p class="text-white text-xl md:text-3xl mb-10 font-normal max-w-4xl mx-auto drop-shadow-md">
                        '.($slideshow->postDetailOne->content ?? 'أسسها صح...').'
                    </p>

                    <div class="opacity-100">
                        <a href="'.url($link).'" class="btn bg-brand-green hover:bg-white hover:text-white border-none text-black px-12 rounded-none font-bold transition-all duration-300 shadow-2xl">
                            إعرف أكثر
                        </a>
                    </div>
                </div>';
            @endphp

            <img src="{{ asset($slideshow->mediaOne->filepath) }}"
            class="slideshow-image h-[125%] sm:w-full object-cover rounded-medium absolute transition-opacity duration-[1500ms] ease-in-out
            {{ $loop->iteration == 1 ? 'opacity-100 z-10' : 'opacity-0 z-0' }}"
            data-text="{{ $textContent }}"
            />
        @endforeach
    </div>

    <div class="absolute inset-0 z-20 pointer-events-none">
        <div class="absolute -top-20 left-0 w-full h-[55%] sm:h-[45%] bg-gradient-to-b from-gray-900/60 to-transparent rounded-medium"></div>
        <div class="absolute -bottom-20 left-0 w-full h-[75%] sm:h-[65%] bg-gradient-to-t from-black/80 to-transparent rounded-medium"></div>

        <div class="absolute inset-0 flex flex-col justify-center items-center px-6">
            <div id="slideshow-text" class="w-full transition-all duration-300 pointer-events-auto">
                </div>

            <div class="flex gap-x-2.5 sm:gap-x-3.5 mt-8 justify-center pointer-events-auto" id="slideshow-dots">
                @foreach ($slideshows as $index => $slideshow)
                    <div
                        class="slideshow-dot cursor-pointer w-2.5 h-2.5 sm:w-3.5 sm:h-3.5 rounded-full transition duration-[1500ms] {{ $loop->first ? 'bg-brand-green opacity-100' : 'bg-white opacity-30' }}">
                    </div>
                @endforeach
            </div>
        </div>

        <button id="prev-slide"
            class="absolute pointer-events-auto cursor-pointer h-20 transition-all duration-300 start-0 sm:start-4 top-1/2 -translate-y-1/2 bg-black/20 hover:bg-brand-green text-white p-5 rounded-2xl backdrop-blur-sm">
            <x-heroicon-m-arrow-right class="h-6 w-6 {{ app()->getLocale() == 'ar' ? '' : 'rotate-180' }}" />
        </button>

        <button id="next-slide"
            class="absolute pointer-events-auto cursor-pointer h-20 transition-all duration-300 end-0 sm:end-4 top-1/2 -translate-y-1/2 bg-black/20 hover:bg-brand-green text-white p-5 rounded-2xl backdrop-blur-sm">
            <x-heroicon-m-arrow-left class="h-6 w-6 {{ app()->getLocale() == 'ar' ? '' : 'rotate-180' }}" />
        </button>
    </div>

   <div class="wave-container">
    <svg viewBox="0 0 1200 120" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">

        <path d="M0,30 C150,130 350,0 600,70 C850,140 1050,30 1200,90 L1200,120 L0,120 Z" class="fill-black"></path>

        <path d="M0,50 C150,140 350,20 600,85 C850,150 1050,50 1200,105 L1200,120 L0,120 Z" class="fill-white"></path>

        <path d="M0,75 C150,155 350,45 600,105 C850,165 1050,85 1200,120 L1200,120 L0,120 Z" class="fill-brand-green"></path>

    </svg>
</div>
</section>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const images = document.querySelectorAll(".slideshow-image");
        const dots = document.querySelectorAll(".slideshow-dot");
        const textBox = document.getElementById("slideshow-text");
        const prevBtn = document.getElementById("prev-slide");
        const nextBtn = document.getElementById("next-slide");

        let current = 0;
        const duration = 7000;
        let timer;

        function updateSlide(index) {
            images.forEach((img, i) => {
                img.classList.toggle("opacity-100", i === index);
                img.classList.toggle("z-10", i === index);
                img.classList.toggle("opacity-0", i !== index);
                img.classList.toggle("z-0", i !== index);

                dots[i].classList.toggle("bg-brand-green", i === index);
                dots[i].classList.toggle("opacity-100", i === index);
                dots[i].classList.toggle("opacity-30", i !== index);
                dots[i].classList.toggle("bg-white", i !== index);
            });

            textBox.innerHTML = images[index].dataset.text;
            current = index;
        }

        function nextSlide() { updateSlide((current + 1) % images.length); }
        function prevSlide() { updateSlide((current - 1 + images.length) % images.length); }

        function startTimer() { timer = setInterval(nextSlide, duration); }
        function stopTimer() { clearInterval(timer); }

        updateSlide(0);
        startTimer();

        dots.forEach((dot, i) => {
            dot.addEventListener("click", () => {
                stopTimer();
                updateSlide(i);
                startTimer();
            });
        });

        nextBtn.addEventListener("click", () => { stopTimer(); nextSlide(); startTimer(); });
        prevBtn.addEventListener("click", () => { stopTimer(); prevSlide(); startTimer(); });
    });
</script> --}}


<style>
    @import url('https://fonts.googleapis.com/css2?family=Changa:wght@400;700;800&display=swap');

    #slideshow-container {
        font-family: 'Changa', sans-serif;
    }

    .text-brand-green { color: #006b36; }
    .bg-brand-green { background-color: #006b36; }

    .text-black-custom {
        color: #000000;
        text-shadow: 0px 0px 1px rgba(255,255,255,0.3);
    }

    #slideshow-text h2, #slideshow-text p, #slideshow-text div {
        animation: slideUpFade 0.8s ease forwards;
    }

    @keyframes slideUpFade {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* تأثير الكتابة - محسّن للشاشات الصغيرة */
    .typing-text {
        position: absolute;
        bottom: 30px;
        right: 40px;
        font-size: 1.25rem;
        font-weight: 700;
        color: #006b36;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.8);
        z-index: 30;
        direction: rtl;
        font-style: bold;
        font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif
    }

    @media (min-width: 640px) {
        .typing-text {
            font-size: 1.5rem;
            bottom: 50px;
        }
    }

    @media (min-width: 1024px) {
        .typing-text {
            font-size: 2rem;
            bottom: 70px;
        }
    }

    .typing-cursor {
        display: inline-block;
        width: 3px;
        height: 1.2em;
        background-color: #006b36;
        margin-right: 5px;
        animation: blink 0.7s infinite;
    }

    @keyframes blink {
        0%, 49% { opacity: 1; }
        50%, 100% { opacity: 0; }
    }

    /* التموج الثابت */
    .wave-container {
        position: absolute;
        bottom: -1px;
        left: 0;
        width: 100%;
        overflow: hidden;
        line-height: 0;
        z-index: 35;
    }

    .wave-container svg {
        position: relative;
        display: block;
        width: calc(100% + 1.3px);
        height: 100px;
    }

    .fill-black { fill: #000000; }
    .fill-white { fill: #ffffff; }
    .fill-brand-green { fill: #006b36; }

</style>

<section class="relative group flex flex-col mt-24 sm:mt-0 h-100 sm:h-screen w-full justify-center items-center overflow-hidden select-none"
    id="slideshow-container">

    <div class="relative h-full w-full overflow-hidden">
        @foreach ($slideshows as $slideshow)
            @php
                $fullTitle = $slideshow->postDetailOne->title;
                $words = explode(' ', trim($fullTitle));
                $firstWord = $words[0] ?? '';
                $remainingTitle = implode(' ', array_slice($words, 1));
                $link = $slideshow->mediaOne->link ?? '#';

                $textContent = '
                <div class="flex flex-col items-center text-center pb-20 sm:pb-44">
                    <h2 class="text-3xl md:text-6xl font-extrabold mb-1 flex flex-wrap justify-center gap-x-2 drop-shadow-2xl leading-tight">
                        <span class="text-brand-green">'.$firstWord.'</span>
                        <span class="text-black-custom">'.$remainingTitle.'</span>
                    </h2>

                    <p class="text-white text-xl md:text-2xl mb-5 font-normal max-w-4xl mx-auto drop-shadow-md">
                        '.($slideshow->postDetailOne->content .'...' ?? 'أسسها صح...').'
                    </p>

                    <div class="opacity-100">
                        <a href="'.url($link).'" class="btn bg-brand-green hover:bg-white hover:text-white border-none text-black px-3 rounded-none font-bold transition-all duration-300 shadow-2xl">
                           '. __("adminlte::adminlte.showmore") .'
                        </a>
                    </div>
                </div>';
            @endphp

            <img src="{{ asset($slideshow->mediaOne->filepath) }}"
            class="slideshow-image h-[125%] sm:w-full object-cover rounded-medium absolute transition-opacity duration-[1500ms] ease-in-out
            {{ $loop->iteration == 1 ? 'opacity-100 z-10' : 'opacity-0 z-0' }}"
            data-text="{{ $textContent }}"
            />
        @endforeach
    </div>

    <div class="absolute inset-0 z-20 pointer-events-none">
        <div class="absolute -top-20 left-0 w-full h-[55%] sm:h-[45%] bg-gradient-to-b from-gray-900/60 to-transparent rounded-medium"></div>
        <div class="absolute -bottom-20 left-0 w-full h-[75%] sm:h-[65%] bg-gradient-to-t from-black/80 to-transparent rounded-medium"></div>

        <div class="absolute inset-0 flex flex-col justify-center items-center px-6">
            <div id="slideshow-text" class="w-full transition-all duration-300 pointer-events-auto">
            </div>

            <div class="flex gap-x-2.5 sm:gap-x-3.5 mt-8 justify-center pointer-events-auto" id="slideshow-dots">
                @foreach ($slideshows as $index => $slideshow)
                    <div
                        class="slideshow-dot cursor-pointer w-2.5 h-2.5 sm:w-3.5 sm:h-3.5 rounded-full transition duration-[1500ms] {{ $loop->first ? 'bg-brand-green opacity-100' : 'bg-white opacity-30' }}">
                    </div>
                @endforeach
            </div>
        </div>

        <!-- نص الكتابة المتحرك - محسّن للشاشات الصغيرة -->
        <div class="typing-text">
            <span id="typed-text"></span>
            <span class="typing-cursor"></span>
        </div>

        <button id="prev-slide"
            class="absolute pointer-events-auto cursor-pointer h-20 transition-all duration-300 start-0 sm:start-4 top-1/2 -translate-y-1/2 bg-black/20 hover:bg-brand-green text-white p-5 rounded-2xl backdrop-blur-sm">
            <x-heroicon-m-arrow-right class="h-6 w-6 {{ app()->getLocale() == 'ar' ? '' : 'rotate-180' }}" />
        </button>

        <button id="next-slide"
            class="absolute pointer-events-auto cursor-pointer h-20 transition-all duration-300 end-0 sm:end-4 top-1/2 -translate-y-1/2 bg-black/20 hover:bg-brand-green text-white p-5 rounded-2xl backdrop-blur-sm">
            <x-heroicon-m-arrow-left class="h-6 w-6 {{ app()->getLocale() == 'ar' ? '' : 'rotate-180' }}" />
        </button>
    </div>

    <!-- التموج الثابت -->
    <div class="wave-container">
        <svg viewBox="0 0 1200 120" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0,30 C150,130 350,0 600,70 C850,140 1050,30 1200,90 L1200,120 L0,120 Z" class="fill-black"></path>
            <path d="M0,50 C150,140 350,20 600,85 C850,150 1050,50 1200,105 L1200,120 L0,120 Z" class="fill-white"></path>
            <path d="M0,75 C150,155 350,45 600,105 C850,165 1050,85 1200,120 L1200,120 L0,120 Z" class="fill-brand-green"></path>
        </svg>
    </div>
</section>

<!-- قسم رؤية الشركة والجودة -->
<section class="relative py-3 bg-gradient-to-b from-white to-gray-50 overflow-hidden">
    <div class="max-w-7xl mx-auto px-6 sm:px-12 relative z-10">
        <div class="flex flex-col items-start gap-3" data-aos="fade-up">
            <!-- النص -->
            <div class="w-full text-right bg-white special_font_nasser">
                <p class="text-lg md:text-xl text-gray-700 leading-relaxed">
                    {{ app()->getLocale() == 'ar' 
                        ? 'الشركة العربية اليمنية للأسمنت.. رؤيةٌ تبني وطناً وإرثٌ من الجودة.. إسمنت حضرمي بجودة عالمية… منذ 2006م..' 
                        : 'The Arab Yemeni Cement Company: A vision that builds a nation and a legacy of quality... Hadrami cement with global quality... since 2006.' }}
                </p>
            </div>
            
            <!-- الزر -->
            <div class="w-full text-right" data-aos="zoom-in" data-aos-delay="200">
                <a href="{{ localizedRoute('products') }}" 
                    class="inline-flex items-center bg-brand-green text-white rtl:pr-12 rtl:pl-2.5 ltr:pl-12 ltr:pr-2.5 rounded-none text-xl hover:bg-black transition-all duration-500 shadow-xl hover:shadow-2xl hover:-translate-y-1 group special_font_nasser">
                    <span>{{ __('adminlte::landingpage.products') }}</span>
                    <div class="bg-white/20 p-2 rounded-full group-hover:bg-white/30 transition-colors ms-4 me-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 {{ app()->getLocale() == 'ar' ? 'rotate-180' : '' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </div>
                </a>
            </div>
        </div>
    </div>
</section>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const images = document.querySelectorAll(".slideshow-image");
        const dots = document.querySelectorAll(".slideshow-dot");
        const textBox = document.getElementById("slideshow-text");
        const typedTextElement = document.getElementById("typed-text");
        const prevBtn = document.getElementById("prev-slide");
        const nextBtn = document.getElementById("next-slide");

        let current = 0;
        // const typingText = "معاً نبني اليمن";
        const typingText = "{{ __('adminlte::landingpage.TogetherWeBuildYemen') }}";
        let typingTimer;
        let slideTimer;
        let isTyping = false;

        function typeText(callback) {
            isTyping = true;
            typedTextElement.textContent = "";
            let charIndex = 0;

            typingTimer = setInterval(() => {
                if (charIndex < typingText.length) {
                    typedTextElement.textContent += typingText[charIndex];
                    charIndex++;
                } else {
                    clearInterval(typingTimer);
                    isTyping = false;
                    setTimeout(() => {
                        if (callback) callback();
                    }, 1000);
                }
            }, 150);
        }

        function updateSlide(index, autoAdvance = true) {
            if (typingTimer) clearInterval(typingTimer);
            if (slideTimer) clearTimeout(slideTimer);

            images.forEach((img, i) => {
                img.classList.toggle("opacity-100", i === index);
                img.classList.toggle("z-10", i === index);
                img.classList.toggle("opacity-0", i !== index);
                img.classList.toggle("z-0", i !== index);

                dots[i].classList.toggle("bg-brand-green", i === index);
                dots[i].classList.toggle("opacity-100", i === index);
                dots[i].classList.toggle("opacity-30", i !== index);
                dots[i].classList.toggle("bg-white", i !== index);
            });

            textBox.innerHTML = images[index].dataset.text;
            current = index;

            if (autoAdvance) {
                typeText(() => {
                    nextSlide();
                });
            } else {
                typeText();
            }
        }

        function nextSlide() {
            updateSlide((current + 1) % images.length);
        }

        function prevSlide() {
            updateSlide((current - 1 + images.length) % images.length);
        }

        updateSlide(0);

        dots.forEach((dot, i) => {
            dot.addEventListener("click", () => {
                updateSlide(i, false);
            });
        });

        nextBtn.addEventListener("click", () => {
            if (typingTimer) clearInterval(typingTimer);
            if (slideTimer) clearTimeout(slideTimer);
            nextSlide();
        });

        prevBtn.addEventListener("click", () => {
            if (typingTimer) clearInterval(typingTimer);
            if (slideTimer) clearTimeout(slideTimer);
            prevSlide();
        });
    });
</script>


