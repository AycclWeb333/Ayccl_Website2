@extends('layouts.app')
@section('css')
@endsection
@section('content')
    <x-hero title="{{ $page->title }}" description="{{ $page->content }}" img="{{ asset($page->background) }}" />

<script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.key') }}"></script>
    <script>
        document.addEventListener('submit', function(e) {
            if (e.target && e.target.id === 'training-form') {
                e.preventDefault();
                grecaptcha.ready(function() {
                    grecaptcha.execute('{{ config('services.recaptcha.key') }}', {action: 'submit_training'}).then(function(token) {
                        document.getElementById('g-recaptcha-response').value = token;
                        e.target.submit();
                    });
                });
            }
        });
    </script>

    <div class="w-[95%] mx-auto " data-aos="fade-up" data-aos-duration="700">

        <div class="min-h-screen flex w-full items-center justify-center p-0 sm:p-6">
            <div class="card w-full sm:max-w-4xl shadow-2xl bg-base-100">
                <div class="card-body py-5 p-0 sm:p-8 text-center sm:text-start">
                    {{-- Session alert removed to use SweetAlert2 popup --}}
                    <h2 class="card-title text-2xl font-bold text-emerald-800">
                        {{ __('adminlte::landingpage.internshipForm') }}
                    </h2>
                    <p class="text-md font-bold text-gray-700">
                        {{ __('adminlte::landingpage.fillInternshipForm') }}
                    </p>

                    <form action="{{ localizedRoute('forms.askTraining') }}" method="POST" id="training-form"
                        class="bg-white rounded-2xl p-2 sm:p-8 space-y-6 w-full mx-auto border border-gray-200" enctype="multipart/form-data">
                        @csrf


                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              
                            <!-- Full Name -->
                            <div class="form-control">
                                <label
                                    class="label font-semibold text-gray-600">{{ __('adminlte::landingpage.fullName') }}</label>
                                <input type="text" name="Full-name"
                                    placeholder="{{ __('adminlte::landingpage.write') . __('adminlte::landingpage.fullName') }}"
                                    class="input input-bordered w-full" required />
                            </div>
                            <!-- Email -->
                            <div class="form-control">
                                <label
                                    class="label font-semibold text-gray-600">{{ __('adminlte::landingpage.email') }}</label>
                                <input type="email" name="email" placeholder="example@email.com"
                                    class="input input-bordered w-full" required />
                            </div>
                            <!-- Phone -->
                            <div class="form-control">
                                <label
                                    class="label font-semibold text-gray-600">{{ __('adminlte::landingpage.phoneNo') }}</label>
                                <input type="tel" name="Phone" placeholder="05xxxxxxxx"
                                    class="input input-bordered w-full" required />
                            </div>
                            <!-- City -->
                            <div class="form-control">
                                <label
                                    class="label font-semibold text-gray-600">{{ __('adminlte::landingpage.city') }}</label>
                                <input type="text" name="city"
                                    placeholder="{{ __('adminlte::landingpage.writeCity') }}"
                                    class="input input-bordered w-full" required />
                            </div>
                            <!-- institution  -->
                            <div class="form-control">
                                <label
                                    class="  font-semibold text-gray-600 text-clip">{{ __('adminlte::landingpage.learningInstitution') }}</label>
                                <input type="text" name="institution"
                                    placeholder="{{ __('adminlte::landingpage.write') . __('adminlte::landingpage.institutionName') }}"
                                    class="input input-bordered w-full" required />
                            </div>

                            <!-- Major -->
                            <div class="form-control">
                                <label
                                    class="label font-semibold text-gray-600">{{ __('adminlte::landingpage.major') }}</label>
                                <input type="text" name="major"
                                    placeholder="{{ __('adminlte::landingpage.write') . __('adminlte::landingpage.major') }}"
                                    class="input input-bordered w-full" required />
                            </div>

                            

                            <!-- Date -->

                            <div class="form-control">
                                <label class="label font-semibold text-gray-600">{{ __('adminlte::landingpage.internshipSuggestedDate') }}</label>
                                <button type="button" popovertarget="cally-popover1" class="input input-border w-full"
                                    id="cally1" style="anchor-name:--cally1">
                                    {{ __('adminlte::landingpage.choosedate') }}
                                </button>
                                <div popover id="cally-popover1" class="dropdown bg-base-100 rounded-box shadow-lg"
                                    style="position-anchor:--cally1">
                                    <calendar-date class="cally" min="{{ \Carbon\Carbon::now()->toDateString() }}"
                                        onchange="this.getRootNode().getElementById('cally1').innerText = this.value; this.getRootNode().getElementById('date_input').value = this.value;this.getRootNode().getElementById('cally-popover1').hidePopover();">
                                        <svg aria-label="Previous" class="fill-current size-4" slot="previous"
                                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                            <path d="M15.75 19.5 8.25 12l7.5-7.5"></path>
                                        </svg>
                                        <svg aria-label="Next" class="fill-current size-4" slot="next"
                                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                            <path d="m8.25 4.5 7.5 7.5-7.5 7.5"></path>
                                        </svg>
                                        <calendar-month></calendar-month>
                                    </calendar-date>
                                </div>
                                <input type="hidden" name="date" id="date_input">
                            </div>

                            <!-- Internship period -->
                            <div class="form-control">
                                <label
                                    class="label font-semibold text-gray-600">{{ __('adminlte::landingpage.internshipPeriod') }}</label>
                                <input type="number" name="internship-period" min="1" max="200" 
                                    placeholder="{{ __('adminlte::landingpage.write') . __('adminlte::landingpage.internshipPeriod') }}"
                                    class="input input-bordered w-full pe-7" required />
                            </div>
                            
                        </div>

                        <!-- Message -->
                        <div class="form-control">
                            <label
                                class="label font-semibold text-gray-600">{{ __('adminlte::landingpage.message') }}</label>
                            <textarea class="textarea textarea-bordered w-full" name="Reason" rows="4"
                                placeholder="{{ __('adminlte::landingpage.addmessage') }}" required></textarea>
                        </div>

                        <!-- Attachment -->
                        <div class="form-control">
                            <label class="label font-semibold text-gray-600" style="font-size: small">{{ __('adminlte::landingpage.attachmentLabel') }}</label>
                            <input type="file" name="attachment" accept="application/pdf,image/*" class="file-input file-input-bordered w-full" />
                            @if ($errors->has('attachment'))
                                <span class="text-red-600 text-sm mt-1">{{ $errors->first('attachment') }}</span>
                            @endif
                        </div>

                        <!-- reCAPTCHA v3 -->
                        <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">
                        @if ($errors->has('g-recaptcha-response'))
                            <div class="text-center my-2">
                                <span class="text-red-600 text-sm">{{ $errors->first('g-recaptcha-response') }}</span>
                            </div>
                        @endif

                        <!-- Hidden Fields -->
                        <input type="hidden" name="_next" value="{{ localizedRoute('customerservice') }}">
                        {{-- <input type="hidden" name="_subject" value="طلب زيارة / استفسار جديد">
                        <input type="hidden" name="_autoresponse" value="شكراً لتواصلك معنا، سنرد عليك قريباً"> --}}

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
@section('jsafter')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if (session('status'))
            Swal.fire({
                title: "{{ __('adminlte::landingpage.emailSentSuccessfully') }}",
                text: "{{ session('status') }}",
                icon: 'success',
                confirmButtonText: "{{ __('adminlte::adminlte.ok') }}",
                confirmButtonColor: '#065f46', 
                width: window.innerWidth < 768 ? '90%' : '500px',
                padding: window.innerWidth < 768 ? '1.5rem' : '2.5rem',
                customClass: {
                    popup: 'rounded-3xl shadow-2xl border-0',
                    confirmButton: 'rounded-xl px-10 py-2 font-bold',
                    title: 'text-xl md:text-2xl',
                    htmlContainer: 'text-sm md:text-base'
                }
            });
        @endif
    </script>
@endsection
