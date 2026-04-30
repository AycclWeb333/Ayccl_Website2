<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class MailSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // For the index page, we'll fetch the main mail settings to display as a "record"
        $settings = Setting::whereIn('para', [
            'mail_from_address',
            'mail_from_name'
        ])->get()->keyBy('para');

        // We prepare a "fake" ID or use the first one available
        $id = $settings['mail_from_address']?->id ?? 1;
        $email = $settings['mail_from_address']?->value ?? 'N/A';
        $fromName = $settings['mail_from_name']?->value ?? 'N/A';

        $data = [
            'id' => $id,
            'email' => $email,
            'from_name' => $fromName
        ];

        return view('admin-panel.settings.index', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $settings = Setting::whereIn('para', [
            'mail_mailer',
            'mail_host',
            'mail_port',
            'mail_username',
            'mail_password',
            'mail_encryption',
            'mail_from_address',
            'mail_from_name',
            'mail_receive_visit',
            'mail_receive_training',
            'mail_receive_job'
        ])->get()->keyBy('para');

        return view('admin-panel.settings.edit', compact('settings'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $keys = [
            'mail_mailer',
            'mail_host',
            'mail_port',
            'mail_username',
            'mail_password',
            'mail_encryption',
            'mail_from_address',
            'mail_from_name',
            'mail_receive_visit',
            'mail_receive_training',
            'mail_receive_job'
        ];

        foreach ($keys as $key) {
            if ($request->has($key)) {
                Setting::updateOrCreate(
                    ['para' => $key],
                    ['value' => $request->input($key)]
                );
            }
        }

        return redirect()->route('admin.settings.mail', ['locale' => app()->getLocale()])
            ->with('edit', __('adminlte::adminlte.succEdit'));
    }
}
