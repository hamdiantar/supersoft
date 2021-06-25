<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Mail\MailSettingRequest;
use App\Models\MailSetting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MailSettingController extends Controller
{
    public function edit()
    {
        if (!auth()->user()->can('view_mail_setting')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        if (authIsSuperAdmin()) {
            return redirect()->back()->with(['message' => __('you are not super admin'), 'alert-type' => 'error']);
        }

        $setting = MailSetting::where('branch_id', auth()->user()->branch_id)->first();

        return view('admin.mail_settings.edit', compact('setting'));
    }

    public function update(MailSettingRequest $request)
    {
        if (!auth()->user()->can('update_mail_setting')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $user = auth()->user();

        $branch = $user->branch;

        if (!$branch) {

            return redirect()->back()->with(['message' => __('branch is required'), 'alert-type' => 'error']);
        }

        $data = $request->validated();

        $data['branch_id']  = $branch->id;

        $types = [

            'customer_request_status',
            'quotation_request_status',
            'sales_invoice_status',
            'sales_invoice_return_status',
            'work_card_send_status',
            'sales_invoice_payments_status',
            'sales_invoice_payments_status',
            'sales_return_payments_status',
            'work_card_payments_status',
            'work_card_status',
            'car_follow_up_status',
            'expenses_status',
            'revenue_status',
        ];

        foreach ($types as $type) {

            $data[$type] = 0;

            if ($request->has($type)) {

                $data[$type] = 1;
            }
        }

        $setting = MailSetting::where('branch_id', $branch->id)->first();

        if ($setting) {

            $setting->update($data);
        }

        if (!$setting) {

            MailSetting::create($data);
        }

        return redirect(route('admin:mail.settings.edit'))
            ->with(['message' => __('Settings Updated Successfully'), 'alert-type' => 'success']);

    }
}
