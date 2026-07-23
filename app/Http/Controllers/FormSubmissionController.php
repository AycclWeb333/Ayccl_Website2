<?php

namespace App\Http\Controllers;

use App\Mail\ContactMail;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use Illuminate\Mail\Mailables\Attachment;

class FormSubmissionController extends Controller
{
    protected function getSetting(string $key, ?string $default = null): ?string
    {
        return Setting::where('para', $key)->value('value') ?? $default;
    }

    public function submitVisit(Request $request): RedirectResponse
    {
       $data = $request->validate([
            'Full-name' => ['required','string','max:255'],
            'email' => ['required','email','max:255'],
            'Phone' => ['required','string','max:50'],
            'city' => ['required','string','max:120'],
            'institution' => ['required','string','max:255'],
            'date' => ['nullable','string','max:40'],
            'Reason' => ['required','string','max:5000'],
            'g-recaptcha-response' => ['required'],
            'attachment' => [
                'nullable',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                function ($attribute, $value, $fail) {
                    $extension = $value->getClientOriginalExtension();
                    $size = $value->getSize() / 1024; // size in KB

                    if (strtolower($extension) === 'pdf') {
                        if ($size > 5120) {
                            $fail(__('adminlte::landingpage.pdfSizeError'));
                        }
                    } else {
                        if ($size > 2048) {
                            $fail(__('adminlte::landingpage.imageSizeError'));
                        }
                    }
                },
            ],
        ]);

        // Verify reCAPTCHA (withoutVerifying() added to bypass local SSL certificate issues)
        $response = Http::withoutVerifying()->asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => config('services.recaptcha.secret'),
            'response' => $request->input('g-recaptcha-response'),
            'remoteip' => $request->ip(),
        ]);

        if (!$response->json('success') || $response->json('score') < 0.5) {
            return back()->withErrors(['g-recaptcha-response' => __('adminlte::landingpage.recaptchaFailed')])->withInput();
        }

        unset($data['g-recaptcha-response']);

        $mailAttachments = [];
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $mailAttachments[] = Attachment::fromPath($file->getRealPath())
                ->as($file->getClientOriginalName())
                ->withMime($file->getClientMimeType());
            
            // Remove file object from data array to avoid issues in email views
            unset($data['attachment']);
        }

        $Visitordata = [
            __('adminlte::landingpage.fullName') => $request->input('Full-name'),
            __('adminlte::landingpage.email') => $request->input('email'),
            __('adminlte::landingpage.phoneNo') => $request->input('Phone'),
            __('adminlte::landingpage.city') => $request->input('city'),
            __('adminlte::landingpage.CurrentInstitution') => $request->input('institution'),
            __('adminlte::landingpage.VisitingSuggestedDate') => $request->input('date'),
            __('adminlte::landingpage.visitingReasonMessage')  => $request->input('Reason'),
        ];
        
        // Get specific recipient for visits, fallback to general address
        $officialTo = $this->getSetting('mail_receive_visit') ?: $this->getSetting('mail_from_address');
        
        // Send confirmation to visitor
        Mail::to($data['email'])->send(new ContactMail(
            payload: [
                'title' => __('adminlte::landingpage.visitingForm'),
                'intro' => __('adminlte::landingpage.fillVisitingForm'),
                'data' => $data,
            ],
            viewName: 'emails.visitor-confirmation',
            mailSubject: __('adminlte::landingpage.emailSentSuccessfully'),
            replyToEmail: $officialTo,
            mailAttachments: $mailAttachments,
        ));

        // Send notification to official mailbox
        if (!empty($officialTo)) {
            Mail::to($officialTo)->send(new ContactMail(
                payload: [
                    'title' => 'New Visit / Inquiry Submission',
                    'intro' => 'Details of the new submission are below:',
                    'data' => $data,
                ],
                viewName: 'emails.admin-notification',
                mailSubject: 'New Visit/Inquiry Submission',
                replyToEmail: $data['email'],
                mailAttachments: $mailAttachments,
            ));
        }
        return back()->with('status', __('adminlte::landingpage.emailSentSuccessfully'));
    }

    public function submitTraining(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'Full-name' => ['required','string','max:255'],
            'email' => ['required','email','max:255'],
            'Phone' => ['required','string','max:50'],
            'city' => ['required','string','max:120'],
            'institution' => ['required','string','max:255'],
            'major' => ['required','string','max:255'],
            'date' => ['nullable','string','max:40'],
            'internship-period' => ['required','integer','min:1','max:200'],
            'Reason' => ['required','string','max:5000'],
            'g-recaptcha-response' => ['required'],
            'attachment' => [
                'nullable',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                function ($attribute, $value, $fail) {
                    $extension = $value->getClientOriginalExtension();
                    $size = $value->getSize() / 1024; // size in KB

                    if (strtolower($extension) === 'pdf') {
                        if ($size > 5120) {
                            $fail(__('adminlte::landingpage.pdfSizeError'));
                        }
                    } else {
                        if ($size > 2048) {
                            $fail(__('adminlte::landingpage.imageSizeError'));
                        }
                    }
                },
            ],
        ]);

        // Verify reCAPTCHA
        $response = Http::withoutVerifying()->asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => config('services.recaptcha.secret'),
            'response' => $request->input('g-recaptcha-response'),
            'remoteip' => $request->ip(),
        ]);

        if (!$response->json('success') || $response->json('score') < 0.5) {
            return back()->withErrors(['g-recaptcha-response' => __('adminlte::landingpage.recaptchaFailed')])->withInput();
        }

        unset($data['g-recaptcha-response']);

        $mailAttachments = [];
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $mailAttachments[] = Attachment::fromPath($file->getRealPath())
                ->as($file->getClientOriginalName())
                ->withMime($file->getClientMimeType());
            
            unset($data['attachment']);
        }

        // Get specific recipient for training, fallback to general address
        $officialTo = $this->getSetting('mail_receive_training') ?: $this->getSetting('mail_from_address');

        // Send confirmation to visitor
        Mail::to($data['email'])->send(new ContactMail(
            payload: [
                'title' => __('adminlte::landingpage.internshipForm'),
                'intro' => __('adminlte::landingpage.fillInternshipForm'),
                'data' => $data,
            ],
            viewName: 'emails.visitor-confirmation',
            mailSubject: __('adminlte::landingpage.emailSentSuccessfully'),
            replyToEmail: $officialTo,
            mailAttachments: $mailAttachments,
        ));

        // Send notification to official mailbox
        if (!empty($officialTo)) {
            Mail::to($officialTo)->send(new ContactMail(
                payload: [
                    'title' => 'New Internship Request Submission',
                    'intro' => 'Details of the new submission are below:',
                    'data' => $data,
                ],
                viewName: 'emails.admin-notification',
                mailSubject: 'New Internship Request',
                replyToEmail: $data['email'],
                mailAttachments: $mailAttachments,
            ));
        }

        return back()->with('status', __('adminlte::landingpage.emailSentSuccessfully'));
    }

    public function submitCustomerService(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'Full-name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'Phone' => ['required', 'string', 'max:50'],
            'city' => ['required', 'string', 'max:120'],
            'service_type' => ['nullable', 'string', 'max:255'],
            'department' => ['nullable', 'string', 'max:255'],
            'Reason' => ['required', 'string', 'max:5000'],
            'g-recaptcha-response' => ['nullable'],
        ]);

        if ($request->filled('g-recaptcha-response')) {
            $response = Http::withoutVerifying()->asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => config('services.recaptcha.secret'),
                'response' => $request->input('g-recaptcha-response'),
                'remoteip' => $request->ip(),
            ]);

            if (!$response->json('success') || $response->json('score') < 0.5) {
                return back()->withErrors(['g-recaptcha-response' => __('adminlte::landingpage.recaptchaFailed')])->withInput();
            }
            unset($data['g-recaptcha-response']);
        }

        $dept = $request->input('department');
        
        $deptEmailKey = match ($dept) {
            'customer_service', __('adminlte::landingpage.customerservice') => 'mail_receive_customer_service',
            'technical_support', __('adminlte::landingpage.technicalSupport') => 'mail_receive_technical_support',
            'sales_marketing', __('adminlte::landingpage.salesAndMarketing') => 'mail_receive_sales_marketing',
            'hr', __('adminlte::landingpage.humanResources') => 'mail_receive_hr',
            default => null,
        };

        $officialTo = null;
        if ($deptEmailKey) {
            $officialTo = $this->getSetting($deptEmailKey);
        }
        if (empty($officialTo)) {
            $officialTo = $this->getSetting('mail_from_address');
        }

        $mailAttachments = [];

        // Send confirmation email to user
        Mail::to($data['email'])->send(new ContactMail(
            payload: [
                'title' => __('adminlte::landingpage.directMessage'),
                'intro' => __('adminlte::landingpage.fillform'),
                'data' => [
                    __('adminlte::landingpage.fullName') => $data['Full-name'],
                    __('adminlte::landingpage.email') => $data['email'],
                    __('adminlte::landingpage.phoneNo') => $data['Phone'],
                    __('adminlte::landingpage.city') => $data['city'],
                    __('adminlte::landingpage.chooseService') => $data['service_type'] ?? '',
                    __('adminlte::landingpage.department') => $data['department'] ?? '',
                    __('adminlte::landingpage.message') => $data['Reason'],
                ],
            ],
            viewName: 'emails.visitor-confirmation',
            mailSubject: __('adminlte::landingpage.emailSentSuccessfully'),
            replyToEmail: $officialTo,
            mailAttachments: $mailAttachments,
        ));

        // Send notification to official department mailbox
        if (!empty($officialTo)) {
            Mail::to($officialTo)->send(new ContactMail(
                payload: [
                    'title' => 'New Customer Service Message',
                    'intro' => 'Details of the new message are below:',
                    'data' => [
                        __('adminlte::landingpage.fullName') => $data['Full-name'],
                        __('adminlte::landingpage.email') => $data['email'],
                        __('adminlte::landingpage.phoneNo') => $data['Phone'],
                        __('adminlte::landingpage.city') => $data['city'],
                        __('adminlte::landingpage.chooseService') => $data['service_type'] ?? '',
                        __('adminlte::landingpage.department') => $data['department'] ?? '',
                        __('adminlte::landingpage.message') => $data['Reason'],
                    ],
                ],
                viewName: 'emails.admin-notification',
                mailSubject: 'New Direct Message / Inquiry',
                replyToEmail: $data['email'],
                mailAttachments: $mailAttachments,
            ));
        }

        return back()->with('status', __('adminlte::landingpage.emailSentSuccessfully'));
    }
}
