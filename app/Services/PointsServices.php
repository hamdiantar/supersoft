<?php


namespace App\Services;


use App\Models\PointLog;
use App\Models\PointSetting;

trait PointsServices
{
    public function addPoints($customer, $log, $invoice, $invoice_type)
    {
        $branch_id = $customer->branch_id;

        $pointsSetting = PointSetting::where('branch_id', $branch_id)->first();

        $purchase_value = $pointsSetting ? $pointsSetting->amount : 1;
        $points_value = $pointsSetting ? $pointsSetting->points : 0;

        $points = intval(($invoice->total / $purchase_value)) * $points_value;

//      ADD POINTS TO USER
        $customer->points += $points;
        $customer->save();

        $logData = [
            'branch_id' => $branch_id,
            'invoice_id' => $invoice->id,
            'customer_id' => $customer->id,
            'points' => $points,
            'amount' => $invoice->total,
            'log' => $log,
            'type' => 'additional',
            'setting_amount' => $purchase_value,
            'setting_points' => $points_value,
        ];

        $logData[$invoice_type] = $invoice->id;

        if ($points) {

//          LOG POINTS
            $this->pointsLog($logData);
        }
    }

    public function pointsLog($logData)
    {
//      LOG POINTS
        PointLog::create($logData);
    }

    public function returnPoints($invoice, $user)
    {

        $points = 0;

        if ($user && $invoice) {

            $points += $invoice->pointLogs->sum('points');

            $user->points -= $points;
            $user->save();

//            $this->pointsLog( $user->id, $invoice->id, $points, 'Order has been canceled');
        }

        return true;
    }

    public function subPoints($customer, $log, $invoice, $invoice_type, $points)
    {

        if ($invoice_type == 'sales_invoice_return_id') {

            $salesInvoice = $invoice->salesInvoice;

            $pointsLog = $salesInvoice ? PointLog::where('sales_invoice_id', $salesInvoice->id)->where('type', 'additional')->first() : null;

            if (!$salesInvoice || !$pointsLog) {

                return false;
            }

            $customerPaidAmount = ($pointsLog->amount -  $invoice->total);

            if ($customerPaidAmount < 0) {

                $customerPaidAmount = 0;
            }

            $customerPoints = $pointsLog->setting_amount ? intval(($customerPaidAmount / $pointsLog->setting_amount)) * $pointsLog->setting_points : 0;

            $points = ($pointsLog->points - $customerPoints);

            if ($points < 0) {

                $points = 0;
            }
        }

        $branch_id = $customer->branch_id;

        $customer->points -= $points;

        if ($customer->points < 0) {
            $customer->points = 0;
        }

        $customer->save();

        $logData = [
            'branch_id' => $branch_id,
            'invoice_id' => $invoice->id,
            'customer_id' => $customer->id,
            'points' => $points,
            'amount' => $invoice->total,
            'log' => $log,
            'type' => 'subtraction',
        ];

        $logData[$invoice_type] = $invoice->id;

        if ($points) {

//          LOG POINTS
            $this->pointsLog($logData);
        }
    }
}
