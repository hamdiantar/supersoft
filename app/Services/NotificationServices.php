<?php


namespace App\Services;


use App\Models\NotificationSetting;
use App\Models\User;
use App\Notifications\CustomerWorkCardStatusNotification;
use App\Notifications\QuotationRequestNotification;
use App\Notifications\QuotationStatusNotification;
use App\Notifications\RegisterCustomersNotifications;
use App\Notifications\ReservationNotification;
use App\Notifications\ReturnSalesInvoiceNotification;
use App\Notifications\SalesInvoiceNotification;
use App\Notifications\SalesInvoicePaymentsNotification;
use App\Notifications\SalesInvoiceReturnPaymentNotification;
use App\Notifications\WorkCardPaymentsNotification;
use App\Notifications\WorkCardStatusNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

trait NotificationServices
{
    public function sendNotification ($type, $sendTo, $data = []) {

        $typesWithNoSettings = ['customer_request', 'customer_reservation'];

        if (!in_array($type, $typesWithNoSettings)) {

            if ($sendTo == 'user') {

                $auth = auth()->guard('customer')->user();

            } else {

                $auth = auth()->user();
            }

            if (!$auth) {

                return false;
            }

            $setting = NotificationSetting::where('branch_id', $auth->branch_id)->first();

            if (!$setting || !$setting->$type) {

                return false;
            }
        }

        if ($type == 'customer_request') {

            $this->customerRequest($data);
        }

        if ($type == 'quotation_request') {

            $this->quotationRequest($data);
        }

        if ($type == 'work_card_status_to_user') {

            $this->workCardStatusToUser($data);
        }

        if ($type == 'quotation_request_status') {

            $this->quotationStatus($data);
        }

        if ($type == 'sales_invoice') {

            $this->salesInvoice($data);
        }

        if ($type == 'return_sales_invoice') {

            $this->salesInvoiceReturn($data);
        }

        if ($type == 'work_card_status_to_customer') {

            $this->workCardStatusToCustomer($data);
        }

        if ($type == 'sales_invoice_payments') {

            $this->salesInvoicePayments($data);
        }

        if ($type == 'return_sales_invoice_payments') {

            $this->salesInvoiceReturnPayments($data);
        }

        if ($type == 'work_card_payments') {

            $this->workCardPayments($data);
        }

        if ($type == 'customer_reservation') {

            $this->customerReservation($data);
        }
    }

    public function adminUsers ($branch_id, $permission_id) {

        $users = DB::table('users')

            ->join('model_has_roles', function ($join) {

                $join->on('users.id', '=', 'model_has_roles.model_id')
                    ->where('model_has_roles.model_type', 'App\Models\User');
            })
            ->join('role_has_permissions', function ($join) use ($permission_id) {

                $join->on('model_has_roles.role_id', '=', 'role_has_permissions.role_id')
                    ->where('role_has_permissions.permission_id', $permission_id);
            })
            ->where('branch_id',$branch_id)
            ->select('id')
            ->get()
            ->pluck('id')
            ->toArray();

        $admins = User::whereIn('id', $users)->get();

        return $admins;
    }

    public function customerRequest ($data) {

        $title = __('Customer Request');
        $message = __('New customer Request Please Check');

        $users = $this->adminUsers($data['customer_request']->branch_id,253);

        if ($users) {

            Notification::send($users, new RegisterCustomersNotifications($title, $message, $data['customer_request']));
        }
    }

    public function quotationRequest ($data) {

        $title = __('Quotation Request');
        $message = __('New Quotation Request Please Check');

        $users = $this->adminUsers($data['branch_id'],257);

        if ($users) {

            Notification::send($users, new QuotationRequestNotification($title, $message, $data['quotation_request']));
        }
    }

    public function workCardStatusToUser ($data) {

        $title = __('Work Card Status');
        $message = __($data['message']);

        $users = $this->adminUsers($data['work_card']->branch_id,76);


        if ($users) {

            Notification::send($users, new WorkCardStatusNotification($title, $message, $data['work_card']));
        }
    }

    public function quotationStatus ($data) {

        $title = __('Quotation Request Status');
        $message = __($data['message']);
        $customer = $data['quotation']->customer;

        if ($customer) {

            Notification::send($customer, new QuotationStatusNotification($title, $message, $data['quotation']));
        }
    }

    public function salesInvoice ($data) {

        $title = __('Sales Invoice');
        $message = __($data['message']);
        $customer = $data['sales_invoice']->customer;

        if ($customer) {

            Notification::send($customer, new SalesInvoiceNotification($title, $message, $data['sales_invoice']));
        }
    }

    public function salesInvoiceReturn ($data) {

        $title = __('Sales Invoice Return');
        $message = __($data['message']);
        $customer = $data['sales_invoice_return']->customer;

        if ($customer) {

            Notification::send($customer, new ReturnSalesInvoiceNotification($title, $message, $data['sales_invoice_return']));
        }
    }

    public function workCardStatusToCustomer ($data) {

        $title = __('Work Card');
        $message = __($data['message']);
        $customer = $data['work_card']->customer;

        if ($customer) {

            Notification::send($customer, new CustomerWorkCardStatusNotification($title, $message, $data['work_card']));
        }
    }

    public function salesInvoicePayments ($data) {

        $title = __('Sales Invoice Payments');
        $message = __($data['message']);
        $customer = $data['sales_invoice']->customer;

        if ($customer) {

            Notification::send($customer, new SalesInvoicePaymentsNotification($title, $message, $data['sales_invoice']));
        }
    }

    public function salesInvoiceReturnPayments ($data) {

        $title = __('Sales Invoice Return Payments');
        $message = $data['message'];
        $customer = $data['sales_invoice_return']->customer;

        if ($customer) {

            Notification::send($customer, new SalesInvoiceReturnPaymentNotification($title, $message, $data['sales_invoice_return']));
        }
    }

    public function workCardPayments ($data) {

        $title = __('Work Card Payments');
        $message = __($data['message']);
        $customer = $data['work_card']->customer;

        if ($customer) {

            Notification::send($customer, new WorkCardPaymentsNotification($title, $message, $data['work_card']));
        }
    }

    public function customerReservation ($data) {

        $title = __('Customer Reservation');

        $message = __('New Customer Reservation Please Check');

        $branch = $data['customer_reservation']->customer->branch;

        $users = $this->adminUsers($branch->id,253);

        if ($users) {

            Notification::send($users, new ReservationNotification($title, $message, $data['customer_reservation']));
        }
    }
}
