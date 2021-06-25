<?php

namespace App\Http\Controllers\Admin;

//use App\Http\Requests\Admin\PointsRules\PointsRuleRequest;
use App\Http\Requests\Admin\PointsRules\PointsRuleRequest;
use App\Models\Branch;
use App\Models\PointRule;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PointsRulesController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->user()->can('view_points_rules')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        try {

            $rules = PointRule::query();

            if ($request->has('search') && $request['search'] != null) {

                $rules->where(function ($q) use ($request) {

                    $q->orWhere('points', $request['search']);
                    $q->orWhere('amount', $request['search']);
                    $q->orWhere('text_ar', 'like', '%' . $request['search'] . '%');
                    $q->orWhere('text_en', 'like', '%' . $request['search'] . '%');
                });
            }

            $rules = $rules->paginate(10);

            // $branches = Branch::all()->pluck('name');

        } catch (\Exception $e) {

            return redirect()->back()->with(['message' => 'sorry please try later ', 'alert-type' => 'error']);
        }

        return view('admin.points_rules.index', compact('rules'));
    }

    public function create()
    {
        if (!auth()->user()->can('create_points_rules')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $branches = Branch::all()->pluck('name');

        return view('admin.points_rules.create', compact('branches'));
    }

    public function store(PointsRuleRequest $request)
    {
        if (!auth()->user()->can('create_points_rules')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        try {

            $data = $request->validated();

            $data['status'] = 0;

            $data['status'] = $request->has('status') ? 1 : 0;

            $data['branch_id'] = authIsSuperAdmin() ? $request['branch_id'] : auth()->user()->branch_id;

            PointRule::create($data);

        } catch (\Exception $e) {

            return redirect()->back()
                ->with(['message' => 'عفوا من فضلك حاول لاحقا', 'alert-type' => 'error']);
        }

        return redirect(route('admin:points-rules.index'))
            ->with(['message' => ' تم بنجاح ', 'alert-type' => 'success']);
    }

    public function show(PointRule $pointsRule)
    {
        if (!auth()->user()->can('view_points_rules')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        return view('admin.points_rules.show', compact('pointsRule'));
    }

    public function edit(PointRule $pointsRule)
    {
        if (!auth()->user()->can('update_points_rules')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $branches = Branch::all()->pluck('name');

        return view('admin.points_rules.edit', compact('pointsRule', 'branches'));
    }

    public function update(PointsRuleRequest $request, PointRule $pointsRule)
    {

        if (!auth()->user()->can('update_points_rules')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        try {

            $data = $request->validated();

            $data['status'] = 0;

            if ($request->has('status')) {
                $data['status'] = 1;
            }

            $pointsRule->update($data);


        } catch (\Exception $e) {

            return redirect()->back()
                ->with(['message' => 'عفوا من فضلك حاول لاحقا ', 'alert-type' => 'error']);

        }

        return redirect(route('admin:points-rules.index'))
            ->with(['message' => ' تم بنجاح ', 'alert-type' => 'success']);
    }

    public function destroy(PointRule $pointsRule)
    {
        if (!auth()->user()->can('delete_points_rules')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        try {

            $pointsRule->forceDelete();

        } catch (\Exception $e) {

            return redirect()->back()->with(['message' => 'عفوا من فضلك حاول لاحقا', 'alert-type' => 'error']);
        }

        return redirect()->back()->with(['message' => 'تم بنجاح', 'alert-type' => 'success']);
    }

    public function deleteSelected(Request $request)
    {
        if (!auth()->user()->can('delete_points_rules')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        if (isset($request->ids)) {
            PointRule::whereIn('id', $request->ids)->forceDelete();
            return redirect()->back()
                ->with(['message' => __('words.selected-row-deleted'), 'alert-type' => 'success']);
        }
        return redirect()->back()
            ->with(['message' => __('words.select-one-least'), 'alert-type' => 'error']);
    }
}
