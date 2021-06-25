<?php

namespace App\Analytics;

use DB;

class ChartAnalytics {
    protected $month ,$year ,$is_branch_required ,$user_authorizations;

    function __construct() {
        $this->month = date("m");
        $this->year = date("Y");
        $this->is_branch_required = authIsSuperAdmin() ? NULL : auth()->user()->branch_id;
        $this->user_authorizations['expenses'] = auth()->user()->can('view_expense_receipts');
        $this->user_authorizations['revenue'] = auth()->user()->can('view_revenue_receipts');

        $this->user_authorizations['sales'] = auth()->user()->can('view_sales_invoices');
        $this->user_authorizations['sales_return'] = auth()->user()->can('view_sales_invoices_return');
        $this->user_authorizations['buy'] = auth()->user()->can('view_purchase_invoices');
        $this->user_authorizations['buy_return'] = auth()->user()->can('view_purchase_return_invoices');
        $this->user_authorizations['card_invoices'] = auth()->user()->can('view_work_card');
    }

    function get() {
        $data['receipts'] = $this->receiptsAnalytics();
        $data['sales'] = $this->salesAnalytics();
        $data['sales-return'] = $this->salesReturnAnalytics();
        $data['buy'] = $this->buyAnalytics();
        $data['buy-return'] = $this->buyReturnAnalytics();
        $data['card-invoices'] = $this->cardInvoicesAnalytics();
        return $data;
    }
    
    private function receiptsAnalytics() {
        $data = [];
        $current_year = $this->year;
        $branch = $this->is_branch_required;
        for($i = 0 ;$i <= 12 ;$i++) {
            if ($this->user_authorizations['expenses']) {
                $data[$i]['expenses'] =
                    \App\Models\ExpensesReceipt::when($branch ,function ($q) use ($branch) {
                        $q->where('branch_id' ,$branch);
                    })->whereMonth('date' ,$i)->whereYear('date' ,$current_year)->sum('cost');
            } else {
                $data[$i]['expenses'] = 0;
            }
            if ($this->user_authorizations['revenue']) {
                $data[$i]['revenue'] =
                    \App\Models\RevenueReceipt::when($branch ,function ($q) use ($branch) {
                        $q->where('branch_id' ,$branch);
                    })->whereMonth('date' ,$i)->whereYear('date' ,$current_year)->sum('cost');
            } else {
                $data[$i]['expenses'] = 0;
            }
        }
        return $data;
    }

    private function salesAnalytics() {
        $return = ['count' => 0, 'amount' => 0 ,'background' => "rgba(112 ,50 ,200 ,0.6)"];
        if ($this->user_authorizations['sales']) {
            $branch = $this->is_branch_required;
            $temp = $sales_query = \App\Models\SalesInvoice
                ::whereMonth('date' ,$this->month)
                ->whereYear('date' ,$this->year)
                ->when($branch ,function ($q) use ($branch) {
                    $q->where('branch_id' ,$branch);
                });
            $return['count'] = $sales_query->count();
            $return['amount'] = $temp->sum('total');
        }
        return $return;
    }

    private function salesReturnAnalytics() {
        $return = ['count' => 0, 'amount' => 0 ,'background' => "rgba(70 ,92 ,220 ,0.6)"];
        if ($this->user_authorizations['sales_return']) {
            $branch = $this->is_branch_required;
            $temp = $sales_return_query = \App\Models\SalesInvoiceReturn
                ::whereMonth('date' ,$this->month)
                ->whereYear('date' ,$this->year)
                ->when($branch ,function ($q) use ($branch) {
                    $q->where('branch_id' ,$branch);
                });
            $return['count'] = $sales_return_query->count();
            $return['amount'] = $temp->sum('total');
        }
        return $return;
    }

    private function buyAnalytics() {
        $return = ['count' => 0, 'amount' => 0 ,'background' => "rgba(26 ,85 ,170 ,0.6)"];
        if ($this->user_authorizations['buy']) {
            $branch = $this->is_branch_required;
            $temp = $buy_query = \App\Models\PurchaseInvoice
                ::whereMonth('date' ,$this->month)
                ->whereYear('date' ,$this->year)
                ->when($branch ,function ($q) use ($branch) {
                    $q->where('branch_id' ,$branch);
                });
            $return['count'] = $buy_query->count();
            $return['amount'] = $temp->sum('total');
        }
        return $return;
    }

    private function buyReturnAnalytics() {
        $return = ['count' => 0, 'amount' => 0 ,'background' => "rgba(90 ,160 ,210 ,0.6)"];
        if ($this->user_authorizations['buy_return']) {
            $branch = $this->is_branch_required;
            $temp = $buy_return_query = \App\Model\PurchaseReturn
                ::whereMonth('date' ,$this->month)
                ->whereYear('date' ,$this->year)
                ->when($branch ,function ($q) use ($branch) {
                    $q->where('branch_id' ,$branch);
                });
            $return['count'] = $buy_return_query->count();
            $return['amount'] = $temp->sum('total_after_discount');
        }
        return $return;
    }

    private function cardInvoicesAnalytics() {
        $return = ['count' => 0, 'amount' => 0 ,'background' => "rgba(130 ,50 ,20 ,0.6)"];
        if ($this->user_authorizations['card_invoices']) {
            $branch = $this->is_branch_required;
            $card_invoices_query = \App\Models\CardInvoice
                ::join('work_cards' ,'work_cards.id' ,'=' ,'card_invoices.work_card_id')
                ->whereMonth('date' ,$this->month)
                ->whereYear('date' ,$this->year)
                ->when($branch ,function ($q) use ($branch) {
                    $q->where('work_cards.branch_id' ,$branch);
                })
                ->select(
                    DB::raw('COUNT(*) as count'),
                    DB::raw('SUM(total) as sum')
                )->first();
            $return['count'] = $card_invoices_query->count;
            $return['amount'] = $card_invoices_query->sum;
        }
        return $return;
    }
}