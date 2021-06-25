<?php
namespace App\AccountingModule\Traits;

use Illuminate\Http\Request;
use App\AccountingModule\Controllers\AccountsTree as TreeController;
use App\AccountingModule\Models\AccountsTree;
use App\AccountingModule\Models\AccountRelation;
use App\AccountingModule\Models\AccountRelations\ActorsRelated;
use Illuminate\Database\Eloquent\Model;

trait AccountRelationGlobalTrait {
    protected function create_actor_relation($related_model_name ,$actor_name ,$related_id) {
        $acc_relation = AccountRelation::create([
            'accounts_tree_root_id' => request('accounts_tree_root_id'),
            'accounts_tree_id' => request('accounts_tree_id'),
            'related_model_name' => $related_model_name,
            'branch_id' => auth()->user()->branch_id
        ]);
        ActorsRelated::create([
            'account_relation_id' => $acc_relation->id,
            'actor_type' => $actor_name,
            'related_as' => request('related_as'),
            'related_id' => $related_id
        ]);
    }

    protected function update_actor_relation(Request $request ,$id ,$related_id) {
        $acc_relation = AccountRelation::findOrFail($id);
        $acc_relation->update([
            'accounts_tree_root_id' => $request->accounts_tree_root_id,
            'accounts_tree_id' => $request->accounts_tree_id,
        ]);
        $acc_relation->related_actor->update([
            'related_as' => $request->related_as,
            'related_id' =>  $related_id
        ]);
    }

    protected function update_acc_relateion(Request $request ,$id) {
        $acc_relation = AccountRelation::findOrFail($id);
        $acc_relation->update([
            'accounts_tree_root_id' => $request->accounts_tree_root_id,
            'accounts_tree_id' => $request->accounts_tree_id,
        ]);
        return $acc_relation;
    }

    protected function create_acc_relation(Request $request ,$related_model_name) {
        return AccountRelation::create([
            'accounts_tree_root_id' => $request->accounts_tree_root_id,
            'accounts_tree_id' => $request->accounts_tree_id,
            'related_model_name' => $related_model_name,
            'branch_id' => auth()->user()->branch_id
        ]);
    }

    protected function get_root_accounts() {
        return AccountsTree::where('tree_level' ,0)->get();
    }

    protected function get_tree_accounts($parent_tree_id) {
        $accounts = [];
        if ($parent_tree_id)
            TreeController::build_tree_ids_array($parent_tree_id ,$accounts ,auth()->user()->branch_id);
        return empty($accounts) ? [] : AccountsTree::whereIn('id' ,$accounts)->get();
    }

    protected function fetch_actors(Model $model) {
        return $model->where('branch_id' ,auth()->user()->branch_id)->select('id' ,'name_ar' ,'name_en')->get();
    }
}