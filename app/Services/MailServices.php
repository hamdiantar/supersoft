<?php


namespace App\Services;

use App\Models\MailSetting;
use Illuminate\Support\Facades\Mail;

trait MailServices
{

    public function sendMail ($mail, $status, $type, $emailClassName) {

        $branch_id = auth()->user()->branch_id;

        $setting = MailSetting::where('branch_id', $branch_id)->first();

        if (!$setting) {

            return false;
        }

        if ($setting->$status == 0) {

            return false;
        }

        $note = $setting->$type;

        Mail::to($mail)->send(new $emailClassName($note));

        return true;
    }

}
