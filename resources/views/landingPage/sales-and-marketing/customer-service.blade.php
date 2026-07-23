@extends('layouts.app')
@section('css')
@endsection
@section('content')
    <x-hero title="{{ $page->title }}" description="{{ $page->content }}" img="{{ asset($page->background) }}" />

    <div class="w-[95%] mx-auto space-y-10 sm:space-y-10" data-aos="fade-up" data-aos-duration="700">
        @isset($posts)
            <div class="space-y-30">
                @foreach ($posts as $index => $post)
                    @php
                        $isEven = $index % 2 === 0;
                        $dir = session('locale') == 'ar' ? 1 : 2;
                        $dir = ($dir + $isEven) % 2 == 0 ? 1 : 0;
                    @endphp
                    <div class="flex flex-col md:flex-row items-center gap-8 lg:gap-16 my-10  shadow-2xl p-2 lg:p-10 py-20 rounded-4xl
                              {{ $isEven ? 'md:flex-row-reverse' : 'md:flex-row' }}"
                        data-aos="fade-up" data-aos-duration="400">

                        {{-- Image --}}
                        {{-- @if (isset($post->mediaOne->filepath))
                            <div class="w-full sm:w-2/3 lg:w-1/2 relative">
                                <img src="{{ asset($post->mediaOne->filepath) }}" alt="{{ $post->mediaOne->alt }}"
                                    class="rounded-2xl shadow-lg w-[100%] p-0 m-0 h-80 sm:h-3/4 object-cover" />

                            </div>
                        @endif --}}
                        @if (isset($post->mediaOne->filepath))
                        <div class="w-full lg:w-1/2 flex justify-center items-center">
                            <div class="relative inline-block group">

                                <div class="relative z-10 overflow-hidden shadow-lg" style="box-shadow: -20px -18px 4px 1px #2d843d; border-radius: 0px;">
                                    <img src="{{ asset($post->mediaOne->filepath) }}"
                                         alt="{{ $post->mediaOne->alt }}"
                                         class="w-full h-80 sm:h-[400px] object-cover block" />
                                </div>
                            </div>
                        </div>


                    @endif

                        {{-- <div class="card-body my-auto space-y-10 w-full lg:w-[40%] {{ $isEven ? 'order-1' : 'order-2' }}"> --}}
                        <div class="w-full lg:w-1/2 lg:text-start">
                            <h2 class="card-title text-2xl xl:text-4xl text-green-900 text-center">
                                {{ $post->postDetailOne->title }}</h2>
                            <ol class="text-md xl:text-xl space-y-4">
                                {!! nl2br($post->postDetailOne->content) !!}
                            </ol>
                        </div>
                    </div>
                @endforeach
            </div>
        @endisset

        <div class="min-h-screen flex w-full items-center justify-center p-0 sm:p-6">
            <div class="card w-full sm:max-w-4xl shadow-2xl bg-base-100">
                <div class="card-body py-5 p-0 sm:p-8 text-center sm:text-start">
                    @if (session('status'))
                        <div id="success-alert" role="alert" class="alert alert-success relative mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>{{ session('status') }}</span>

                            <!-- Close Button -->
                            <button type="button"
                                onclick="document.getElementById('success-alert').classList.add('hidden')"
                                class="absolute end-2 p-2 cursor-pointer text-gray-800 hover:text-black hover:bg-gray-100 rounded-4xl transition-all duration-300">
                                ✕
                            </button>
                        </div>
                    @endif
                    <h2 class="card-title text-2xl font-bold text-emerald-800">
                        {{ __('adminlte::landingpage.directMessage') }}
                    </h2>
                    <p class="text-md font-bold text-gray-700">
                        {{ __('adminlte::landingpage.fillform') }}
                    </p>

                    <form action="{{ localizedRoute('forms.customerService') }}" method="POST"
                        class="bg-white rounded-2xl p-2 sm:p-8 space-y-6 w-full mx-auto border border-gray-200">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Message Type -->
                            <div class="form-contrl w-full">
                                <label class="label font-semibold text-gray-600">{{ __('adminlte::landingpage.chooseService') }}</label>
                                <select name="service_type"
                                    data-hs-select='{
                                    "placeholder": "{{ __('adminlte::landingpage.chooseService') }}",
                                    "toggleTag": "<button type=\"button\" aria-expanded=\"false\"></button>",
                                    "toggleClasses": "hs-select-disabled:pointer-events-none hs-select-disabled:opacity-50 relative py-3 ps-4 pe-9 flex gap-x-2 text-nowrap w-full cursor-pointer bg-white border border-gray-200 rounded-lg text-start text-sm focus:outline-hidden focus:ring-2 focus:ring-blue-500",
                                    "dropdownClasses": "mt-2 z-50 w-full max-h-72 p-1 space-y-0.5 bg-white border border-gray-200 rounded-lg overflow-hidden overflow-y-auto [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:bg-gray-300",
                                    "dropdownVerticalFixedPlacement": "bottom",
                                    "optionClasses": "py-2 px-4 w-full text-sm text-gray-800 cursor-pointer hover:bg-gray-100 rounded-lg focus:outline-hidden focus:bg-gray-100 hs-select-disabled:pointer-events-none hs-select-disabled:opacity-50",
                                    "optionTemplate": "<div class=\"flex justify-between items-center w-full\"><span data-title></span><span class=\"hidden hs-selected:block\"><svg class=\"shrink-0 size-3.5 text-blue-600\" xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><polyline points=\"20 6 9 17 4 12\"/></svg></span></div>",
                                    "extraMarkup": "<div class=\"absolute top-1/2 end-3 -translate-y-1/2\"><svg class=\"shrink-0 size-3.5 text-gray-500\" xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><path d=\"m7 15 5 5 5-5\"/><path d=\"m7 9 5-5 5 5\"/></svg></div>"
                                  }'
                                    class="hidden">
                                    <option value="">{{ __('adminlte::landingpage.chooseService') }}        </option>
                                    <option value="{{ __('adminlte::landingpage.order') }}">      {{ __('adminlte::landingpage.order') }}     </option>
                                    <option value="{{ __('adminlte::landingpage.enquiry') }}">   {{ __('adminlte::landingpage.enquiry') }}   </option>
                                    <option value="{{ __('adminlte::landingpage.complaint') }}">  {{ __('adminlte::landingpage.complaint') }} </option>
                                    <option value="{{ __('adminlte::landingpage.suggestion') }}"> {{ __('adminlte::landingpage.suggestion') }} </option>
                                </select>
                            </div>

                            <!-- Department -->
                            <div class="form-control">
                                <label class="label font-semibold text-gray-600">{{ __('adminlte::landingpage.department') }}</label>
                                <select name="department"
                                    data-hs-select='{
                                    "placeholder": "{{ __('adminlte::landingpage.department') }}",
                                    "toggleTag": "<button type=\"button\" aria-expanded=\"false\"></button>",
                                    "toggleClasses": "hs-select-disabled:pointer-events-none hs-select-disabled:opacity-50 relative py-3 ps-4 pe-9 flex gap-x-2 text-nowrap w-full cursor-pointer bg-white border border-gray-200 rounded-lg text-start text-sm focus:outline-hidden focus:ring-2 focus:ring-blue-500",
                                    "dropdownClasses": "mt-2 z-50 w-full max-h-72 p-1 space-y-0.5 bg-white border border-gray-200 rounded-lg overflow-hidden overflow-y-auto [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:bg-gray-300",
                                    "dropdownVerticalFixedPlacement": "bottom",
                                    "optionClasses": "py-2 px-4 w-full text-sm text-gray-800 cursor-pointer hover:bg-gray-100 rounded-lg focus:outline-hidden focus:bg-gray-100 hs-select-disabled:pointer-events-none hs-select-disabled:opacity-50",
                                    "optionTemplate": "<div class=\"flex justify-between items-center w-full\"><span data-title></span><span class=\"hidden hs-selected:block\"><svg class=\"shrink-0 size-3.5 text-blue-600\" xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><polyline points=\"20 6 9 17 4 12\"/></svg></span></div>",
                                    "extraMarkup": "<div class=\"absolute top-1/2 end-3 -translate-y-1/2\"><svg class=\"shrink-0 size-3.5 text-gray-500\" xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><path d=\"m7 15 5 5 5-5\"/><path d=\"m7 9 5-5 5 5\"/></svg></div>"
                                  }'
                                    class="hidden">
                                    <option value="">  {{ __('adminlte::landingpage.department') }}   </option>
                                    <option value="{{ __('adminlte::landingpage.customerservice') }}">  {{ __('adminlte::landingpage.customerservice') }}   </option>
                                    <option value="{{ __('adminlte::landingpage.technicalSupport') }}"> {{ __('adminlte::landingpage.technicalSupport') }}   </option>
                                    <option value="{{ __('adminlte::landingpage.salesAndMarketing') }}">{{ __('adminlte::landingpage.salesAndMarketing') }}   </option>
                                    <option value="{{ __('adminlte::landingpage.humanResources') }}">   {{ __('adminlte::landingpage.humanResources') }}   </option>
                                </select>
                            </div>

                            <!-- Full Name -->
                            <div class="form-control">
                                <label class="label font-semibold text-gray-600">{{ __('adminlte::landingpage.fullName') }}</label>
                                <input type="text" name="Full-name" placeholder="{{ __('adminlte::landingpage.fullName') }}"
                                    class="input input-bordered w-full" required />
                            </div>


                            <!-- Email -->
                            <div class="form-control">
                                <label class="label font-semibold text-gray-600">{{ __('adminlte::landingpage.email') }}</label>
                                <input type="email" name="email" placeholder="example@email.com"
                                    class="input input-bordered w-full" required />
                            </div>

                            <!-- Phone -->
                            <div class="form-control">
                                <label class="label font-semibold text-gray-600">{{ __('adminlte::landingpage.phoneNo') }}</label>
                                <input type="tel" name="Phone" placeholder="05xxxxxxxx"
                                    class="input input-bordered w-full" required />
                            </div>

                            <!-- City -->
                            <div class="form-control">
                                <label class="label font-semibold text-gray-600">{{ __('adminlte::landingpage.city') }}</label>
                                <input type="text" name="city" placeholder="{{ __('adminlte::landingpage.writeCity') }}"
                                    class="input input-bordered w-full" required />
                            </div>
                        </div>

                        <!-- Message -->
                        <div class="form-control">
                            <label class="label font-semibold text-gray-600">{{ __('adminlte::landingpage.message') }}</label>
                            <textarea class="textarea textarea-bordered w-full" name="Reason" rows="4"
                                placeholder="{{ __('adminlte::landingpage.addmessage') }}" required></textarea>
                        </div>

                        <!-- Submit -->
                        <div class="form-control mt-6">
                            <button class="btn btn-success btn-lg w-full gap-2" type="submit">
                                <x-heroicon-c-paper-airplane class="w-5 h-5" />
                                {{ __('adminlte::landingpage.submit') }}
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>

    </div>

@endsection
@section('jsafter')
@endsection
