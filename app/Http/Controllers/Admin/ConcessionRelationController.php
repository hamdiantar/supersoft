<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\ConcessionRelation\CreateRequest;
use App\Models\ConcessionType;
use App\Models\ConcessionTypeItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ConcessionRelationController extends Controller
{
    public function index () {

        $types = ConcessionType::where('concession_type_item_id','!=', null)->get();
        return view('admin.concession_relation.index', compact('types'));
    }

    public function createRelation () {

        $types = ConcessionType::get();

        $concessionItems = ConcessionTypeItem::select('id','name', 'type')->get();

        return view('admin.concession_relation.create', compact('types', 'concessionItems'));
    }

    public function store (CreateRequest $request) {

        try {

            $type = $request['type'];

            $concessionType = ConcessionType::find($request['concession_type_id']);

            $concessionTypeItem = ConcessionTypeItem::find($request['concession_item_id']);

            if ($concessionType->type != $type || $concessionTypeItem->type != $type) {

                return redirect()->back()
                    ->with(['message' => __('sorry, type not valid of types or items'), 'alert-type' => 'error']);
            }

            $concessionType->concession_type_item_id = $concessionTypeItem->id;

            $concessionType->save();

            return redirect()->back()->with(['message' => __('words.concession-relation-successfully'), 'alert-type' => 'success']);

        }catch (\Exception $e) {

            return redirect()->back()
                ->with(['message' => __('sorry, please try later'), 'alert-type' => 'error']);
        }
    }

    public function edit(ConcessionType $type) {

        $types = ConcessionType::get();

        $concessionItems = ConcessionTypeItem::select('id','name', 'type')->get();

        return view('admin.concession_relation.edit', compact('types', 'concessionItems', 'type'));
    }

    public function update (CreateRequest $request, ConcessionType $type) {

        try {

            $concessionTypeItem = ConcessionTypeItem::find($request['concession_item_id']);

            if ($type->type != $type || $concessionTypeItem->type != $type) {

                return redirect()->back()
                    ->with(['message' => __('sorry, type not valid of types or items'), 'alert-type' => 'error']);
            }

            $type->concession_type_item_id = $concessionTypeItem->id;

            $type->save();

            return redirect(route(''))->with(['message' => __('words.concession-relation-successfully'), 'alert-type' => 'success']);

        }catch (\Exception $e) {

            return redirect()->back()
                ->with(['message' => __('sorry, please try later'), 'alert-type' => 'error']);
        }
    }
}
