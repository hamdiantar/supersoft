<?php
namespace App\Http\Controllers\Admin\MoneyPermissions;

use Exception;
use App\Models\EmployeeData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

trait ReceiveTrait {

    function reject($id) {
        if (!auth()->user()->can('reject_'.$this->get_invoker_name().'_receive_permissions')) {
            return redirect(route('admin:'.$this->get_invoker_name().'-receives.index'))->with(['authorization' => __('words.unauthorized')]);
        }
        try {
            $receive = $this->receive_maintainable($id);
        } catch (Exception $e) {
            return redirect(route('admin:'.$this->get_invoker_name().'-receives.index'))->with([
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            ]);
        }
        $receive->update(['status' => 'rejected']);
        return redirect(route('admin:'.$this->get_invoker_name().'-receives.index'))->with([
            'message' => __('words.permission-is-rejected'),
            'alert-type' => 'success'
        ]);
    }

    function edit($id) {
        if (!auth()->user()->can('edit_'.$this->get_invoker_name().'_receive_permissions')) {
            return redirect(route('admin:'.$this->get_invoker_name().'-receives.index'))->with(['authorization' => __('words.unauthorized')]);
        }
        try {
            $receive = $this->receive_maintainable($id);
            $employees = EmployeeData::select('id' ,'name_ar' ,'name_en')->get();
        } catch (Exception $e) {
            return redirect(route('admin:'.$this->get_invoker_name().'-receives.index'))->with([
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            ]);
        }
        return  view('admin.money-permissions.'.$this->get_invoker_name().'-receive.edit' ,['model' => $receive ,'employees' => $employees]);
    }

    function run_update($req ,$id) {
        try {
            $receive = $this->receive_maintainable($id);
        } catch (Exception $e) {
            return redirect(route('admin:'.$this->get_invoker_name().'-receives.index'))->with([
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            ]);
        }
        $data = [
            'cost_center_id' => $req->cost_center_id,
            'operation_date' => $req->operation_date,
            'employee_id' => $req->employee_id,
            'note' => $req->note,
        ];
        $receive->update($data);
        return redirect(route('admin:'.$this->get_invoker_name().'-receives.index'))->with([
            'message' => __('words.permission-is-updated'),
            'alert-type' => 'success'
        ]);
    }

    function destroy($id) {
        if (!auth()->user()->can('delete_'.$this->get_invoker_name().'_receive_permissions')) {
            return redirect(route('admin:'.$this->get_invoker_name().'-receives.index'))->with(['authorization' => __('words.unauthorized')]);
        }
        try {
            $receive = $this->receive_maintainable($id);
        } catch (Exception $e) {
            return redirect(route('admin:'.$this->get_invoker_name().'-receives.index'))->with([
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            ]);
        }
        $receive->exchange_permission->update([''.$this->get_invoker_name().'_receive_permission_id' => NULL]);
        $receive->delete();
        return redirect(route('admin:'.$this->get_invoker_name().'-receives.index'))->with([
            'message' => __('words.permission-is-deleted'), 'alert-type' => 'success'
        ]);
    }

    function delete_selected(Request $req) {
        if (!auth()->user()->can('delete_'.$this->get_invoker_name().'_receive_permissions')) {
            return redirect(route('admin:'.$this->get_invoker_name().'-receives.index'))->with(['authorization' => __('words.unauthorized')]);
        }
        if (!$req->has('ids')) {
            return redirect(route('admin:'.$this->get_invoker_name().'-receives.index'))->with([
                'message' => __('words.select-permission-first'),
                'alert-type' => 'error'
            ]);
        }
        DB::beginTransaction();
        foreach($req->ids as $index => $_id) {
            if ($index == 0) continue;
            try {
                $receive = $this->receive_maintainable($_id);
            } catch (Exception $e) {
                DB::rollBack();
                return redirect(route('admin:'.$this->get_invoker_name().'-receives.index'))->with([
                    'message' => $e->getMessage(),
                    'alert-type' => 'error'
                ]);
            }
            $receive->exchange_permission->update([''.$this->get_invoker_name().'_receive_permission_id' => NULL]);
            $receive->delete();
        }
        DB::commit();
        return redirect(route('admin:'.$this->get_invoker_name().'-receives.index'))->with([
            'message' => __('words.permissions-is-deleted'), 'alert-type' => 'success'
        ]);
    }
}