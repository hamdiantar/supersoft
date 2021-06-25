<?php

namespace App\Http\Controllers\Admin;

use App\Models\NotificationSetting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NotificationSettingsController extends Controller
{
    public function edit()
    {
        if (!auth()->user()->can('view_notification_setting')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        if (authIsSuperAdmin()) {
            return redirect()->back()->with(['message' => __('you are not super admin'), 'alert-type' => 'error']);
        }

        $setting = NotificationSetting::where('branch_id', auth()->user()->branch_id)->first();

        return view('admin.notification_settings.edit', compact('setting'));
    }

    public function update(Request $request)
    {
        if (!auth()->user()->can('update_notification_setting')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $user = auth()->user();

        $branch = $user->branch;

        if (!$branch) {

            return redirect(route('admin:notification.settings.edit'))
                ->with(['message' => __('branch is required'), 'alert-type' => 'error']);
        }

        $data = ['branch_id' => $branch->id];

        $types = [

            'customer_request',
            'quotation_request',
            'work_card_status_to_user',
            'minimum_parts_request',
            'end_work_card_employee',
            'end_residence_employee',
            'end_medical_insurance_employee',
            'quotation_request_status',
            'sales_invoice',
            'return_sales_invoice',
            'work_card',
            'work_card_status_to_customer',
            'sales_invoice_payments',
            'return_sales_invoice_payments',
            'work_card_payments',
            'follow_up_cars',
        ];

        foreach ($types as $type) {

            $data[$type] = 0;

            if ($request->has($type)) {

                $data[$type] = 1;
            }
        }

        $setting = NotificationSetting::where('branch_id', $branch->id)->first();

        if ($setting) {

            $setting->update($data);
        }

        if (!$setting) {

            NotificationSetting::create($data);
        }

        return redirect(route('admin:notification.settings.edit'))
            ->with(['message' => __('Settings Updated Successfully'), 'alert-type' => 'success']);

    }
}
