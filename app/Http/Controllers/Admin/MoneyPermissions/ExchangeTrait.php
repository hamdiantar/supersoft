<?php
namespace App\Http\Controllers\Admin\MoneyPermissions;

use Exception;
use App\Models\EmployeeData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

trait ExchangeTrait {
    
    function delete_selected(Request $req) {
        if (!auth()->user()->can('delete_'.$this->get_invoker_name().'_exchange_permissions')) {
            return redirect(route('admin:'.$this->get_invoker_name().'-exchanges.index'))->with(['authorization' => __('words.unauthorized')]);
        }
        if (!$req->has('ids')) {
            return redirect(route('admin:'.$this->get_invoker_name().'-exchanges.index'))->with([
                'message' => __('words.select-permission-first'),
                'alert-type' => 'error'
            ]);
        }
        DB::beginTransaction();
        foreach($req->ids as $index => $exchange_id) {
            if ($index == 0) continue;
            try {
                $exchange = $this->exchange_maintainable($exchange_id);
            } catch (Exception $e) {
                DB::rollBack();
                return redirect(route('admin:'.$this->get_invoker_name().'-exchanges.index'))->with([
                    'message' => $e->getMessage(),
                    'alert-type' => 'error'
                ]);
            }
            $exchange->delete();
        }
        DB::commit();
        return redirect(route('admin:'.$this->get_invoker_name().'-exchanges.index'))->with([
            'message' => __('words.permissions-is-deleted'), 'alert-type' => 'success'
        ]);
    }

    function destroy($id) {
        if (!auth()->user()->can('delete_'.$this->get_invoker_name().'_exchange_permissions')) {
            return redirect(route('admin:'.$this->get_invoker_name().'-exchanges.index'))->with(['authorization' => __('words.unauthorized')]);
        }
        try {
            $exchange = $this->exchange_maintainable($id);
        } catch (Exception $e) {
            return redirect(route('admin:'.$this->get_invoker_name().'-exchanges.index'))->with([
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            ]);
        }
        $exchange->delete();
        return redirect(route('admin:'.$this->get_invoker_name().'-exchanges.index'))->with([
            'message' => __('words.permission-is-deleted'), 'alert-type' => 'success'
        ]);
    }

    function reject($id) {
        if (!auth()->user()->can('reject_'.$this->get_invoker_name().'_exchange_permissions')) {
            return redirect(route('admin:'.$this->get_invoker_name().'-exchanges.index'))->with(['authorization' => __('words.unauthorized')]);
        }
        try {
            $exchange = $this->exchange_maintainable($id);
        } catch (Exception $e) {
            return redirect(route('admin:'.$this->get_invoker_name().'-exchanges.index'))->with([
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            ]);
        }
        $exchange->update(['status' => 'rejected']);
        return redirect(route('admin:'.$this->get_invoker_name().'-exchanges.index'))->with([
            'message' => __('words.permission-is-rejected'),
            'alert-type' => 'success'
        ]);
    }

    function edit($id) {
        if (!auth()->user()->can('edit_'.$this->get_invoker_name().'_exchange_permissions')) {
            return redirect(route('admin:'.$this->get_invoker_name().'-exchanges.index'))->with(['authorization' => __('words.unauthorized')]);
        }
        try {
            $exchange = $this->exchange_maintainable($id);
        } catch (Exception $e) {
            return redirect(route('admin:'.$this->get_invoker_name().'-exchanges.index'))->with([
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            ]);
        }
        $money_source = $this->get_money_sources($exchange);
        $money_destination = $this->get_money_destinations($exchange);
        $employees = EmployeeData::select('id' ,'name_ar' ,'name_en')->get();
        return view('admin.money-permissions.'.$this->get_invoker_name().'-exchange.edit' ,[
            'money_source' => $money_source,
            'employees' => $employees,
            'model' => $exchange,
            'money_destination' => $money_destination
        ]);
    }

    function run_update($req ,$id) {
        try {
            $exchange = $this->exchange_maintainable($id);
        } catch (Exception $e) {
            return redirect(route('admin:'.$this->get_invoker_name().'-exchanges.index'))->with([
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            ]);
        }
        $data = $req->all();
        if (!authIsSuperAdmin()) unset($data['branch_id']);
        $exchange->update($data);
        return redirect(route('admin:'.$this->get_invoker_name().'-exchanges.index'))->with([
            'message' => __('words.permission-is-updated'),
            'alert-type' => 'success'
        ]);
    }
}