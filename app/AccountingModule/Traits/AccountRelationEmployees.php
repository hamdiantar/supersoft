<?php
namespace App\AccountingModule\Traits;

use Exception;
use App\Models\EmployeeData;
use App\Models\EmployeeSetting;
use App\AccountingModule\Models\AccountRelation;
use App\AccountingModule\Models\AccountRelations\ActorsRelated;
use App\Http\Requests\AccountingModule\AccountRelations\EmployeeRequest;

trait AccountRelationEmployees {
    private function employees_model_name() {
        return '\App\AccountingModule\Models\AccountRelations\ActorsRelated';
    }

    protected function create_employees() {
        return view($this->main_view_path.'.create' ,[
            'action_for' => 'employees',
            'view_path' => $this->main_view_path.'.create.employees-form',
            'validation_class' => 'App\Http\Requests\AccountingModule\AccountRelations\EmployeeRequest',
            'form_id' => '#account-relations-employees-form',
            'script_file_path' => $this->main_view_path.'.create.actor-common-script',
            'root_accounts_tree' => $this->get_root_accounts(),
            'form_route' => route('account-relations.store-employees'),
            'employees' => $this->fetch_actors(new EmployeeData()),
            'groups' => $this->fetch_actors(new EmployeeSetting)
        ]);
    }

    function store_employees(EmployeeRequest $request) {
        $related_id = '0';
        if ($request->related_as == 'actor_group') $related_id = $request->group_id;
        else if ($request->related_as == 'actor_id') $related_id = $request->employee_id;
        $this->create_actor_relation(
            $this->employees_model_name(),
            'employee',
            $related_id
        );
        return redirect(route('account-relations.index'))->with([
            'message' => __('accounting-module.account-relation-created') ,'alert-type' => 'success'
        ]);
    }

    protected function relation_employees_unique() {
        $data = request()->all();
        if (
            isset($data['related_as']) && isset($data['accounts_tree_root_id']) && isset($data['accounts_tree_id'])
        ) {
            $where = ['actor_type' => 'employee' ,'related_as' => $data['related_as']];
            if (isset($data['employee_id'])) $where['related_id'] = $data['employee_id'];
            $employee_related = ActorsRelated::where($where)->first();
            if ($employee_related && isset($data['old_id']) && $employee_related->account_relation_id == $data['old_id'])
                return response(['message' => __('accounting-module.relation-not-exists-before')]);
            if ($employee_related) return response(['message' => __('accounting-module.relation-exists-before')] ,400);
            return response(['message' => __('accounting-module.relation-not-exists-before')]);
        }
        return response(['message' => __('accounting-module.please-fill-form')] ,400);
    }

    protected function edit_employees(AccountRelation $account_relation) {
        return view($this->main_view_path.'.edit' ,[
            'model' => $account_relation,
            'form_route' => route('account-relations.update-employees' ,['id' => $account_relation->id]),
            'groups' => $this->fetch_actors(new EmployeeSetting),
            'employees' => $this->fetch_actors(new EmployeeData),
            'root_accounts_tree' => $this->get_root_accounts(),
            'accounts_tree' => $this->get_tree_accounts($account_relation->account_tree_root),
            'view_path' => $this->main_view_path.'.edit.employee-edit',
            'validation_class' => 'App\Http\Requests\AccountingModule\AccountRelations\EmployeeRequest',
            'form_id' => '#account-relations-employees-form',
            'script_file_path' => $this->main_view_path.'.create.actor-common-script',
        ]);
    }

    function update_employees(EmployeeRequest $request ,$id) {
        $related_id = NULL;
        if ($request->related_as == 'actor_group') $related_id = $request->group_id;
        else if ($request->related_as == 'actor_id') $related_id = $request->employee_id;
        try {
            $this->update_actor_relation($request ,$id ,$related_id);
        } catch (Exception $e) {
            return redirect(route('account-relations.index'))->with([
                'message' => __('accounting-module.data-not-found') ,'alert-type' => 'error'
            ]);
        }
        return redirect(route('account-relations.index'))->with([
            'message' => __('accounting-module.account-relation-updated') ,'alert-type' => 'success'
        ]);
    }
}