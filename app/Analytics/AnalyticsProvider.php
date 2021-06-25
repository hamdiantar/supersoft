<?php

namespace App\Analytics;

class AnalyticsProvider {
    static function getDashboardAnalytics() {
        $data['default_actions'] = DefaultActions::get();
        $is_branch_required = authIsSuperAdmin() ? NULL : auth()->user()->branch_id;
        $last_work_cards = auth()->user()->can('view_work_card') ?
            \App\Models\WorkCard::when($is_branch_required ,function ($q) use ($is_branch_required) {
                $q->where('branch_id' ,$is_branch_required);
            })->with(['cardInvoice' ,'customer' ,'user'])->orderBy('id' ,'desc')->limit(10)->get()
            :
            NULL;
        $last_sale_invoices = auth()->user()->can('view_work_card') ?
            \App\Models\SalesInvoice::when($is_branch_required ,function ($q) use ($is_branch_required) {
                $q->where('branch_id' ,$is_branch_required);
            })->with(['customer' ,'created_by'])->orderBy('id' ,'desc')->limit(10)->get()
            :
            NULL;
        $data['chart_analytics'] = (new ChartAnalytics)->get();
        $invoices = $data['chart_analytics'];
        unset($invoices['receipts']);
        return view('analytics-dashboard' ,compact('data' ,'last_sale_invoices' ,'last_work_cards' ,'invoices'));
    }
}
