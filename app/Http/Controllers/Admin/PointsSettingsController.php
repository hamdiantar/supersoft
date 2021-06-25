<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Mail\MailSettingRequest;
use App\Http\Requests\Admin\PointSettings\CreateRequest;
use App\Models\MailSetting;
use App\Models\PointSetting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PointsSettingsController extends Controller
{
    public function edit()
    {
        if (!auth()->user()->can('view_points_setting')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        if (authIsSuperAdmin()) {
            return redirect()->back()->with(['message' => __('you are not super admin'), 'alert-type' => 'error']);
        }

        $setting = PointSetting::where('branch_id', auth()->user()->branch_id)->first();

        return view('admin.points_settings.edit', compact('setting'));
    }

    public function update(CreateRequest $request)
    {
        if (!auth()->user()->can('update_points_setting')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $user = auth()->user();

        $branch = $user->branch;

        if (!$branch) {

            return redirect()->back()->with(['message' => __('branch is required'), 'alert-type' => 'error']);
        }

        $data = [
            'points'=> $request['points'],
            'amount'=> $request['amount'],
            'branch_id'=> $branch->id
        ];

        $data['branch_id']  = $branch->id;


        $setting = PointSetting::where('branch_id', $branch->id)->first();

        if ($setting) {

            $setting->update($data);
        }

        if (!$setting) {

            PointSetting::create($data);
        }

        return redirect()->back()->with(['message' => __('Settings Updated Successfully'), 'alert-type' => 'success']);
    }
}
