<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Models\Activity;

class ActivityLogsController extends Controller
{

    public function __construct()
    {
//        $this->middleware('permission:view_logs');
//        $this->middleware('permission:create_c',['only'=>['create','store']]);
//        $this->middleware('permission:update_coupon',['only'=>['edit','update']]);
//        $this->middleware('permission:delete_logs',['only'=>['delete']]);
    }

    public function index(Request $request){

        if (!auth()->user()->can('view_logs')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $loggedActivity = Activity::where('is_archive', 0)->with('causer')->orderBy('created_at','desc');

        if($request->has('user') && $request['user'] != '')
            $loggedActivity->where('causer_id',$request['user']);

        if($request->has('model') && $request['model'] != '')
            $loggedActivity->where('subject_type','like','%'.$request['model'].'%');

        $loggedActivity = $loggedActivity->get();

        return view('admin.activity_log.index',compact('loggedActivity'));
    }

    public function archive(Request $request)
    {
        if (!auth()->user()->can('view_logs')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $loggedActivity = Activity::where('is_archive', 1)->with('causer')->orderBy('created_at','desc');

        if($request->has('user') && $request['user'] != '')
            $loggedActivity->where('causer_id',$request['user']);

        if($request->has('model') && $request['model'] != '')
            $loggedActivity->where('subject_type','like','%'.$request['model'].'%');

        $loggedActivity = $loggedActivity->get();

        return view('admin.activity_log.archive',compact('loggedActivity'));
    }

    public function delete(Activity $activity){

        if (!auth()->user()->can('delete_logs')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $activity->delete();
        return redirect()->back()->with(['message'=> __('words.log-deleted'),'alert-type'=>'success']);
    }

    public function deleteAll()
    {
        DB::table('activity_log')->truncate();
        return redirect()->back()->with(['message'=> __('words.log-deleted'),'alert-type'=>'success']);
    }

    public function deleteSelected(Request $request)
    {
        if (!auth()->user()->can('delete_logs')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }
        if (isset($request->ids) && isset($request->archive)) {
            DB::table('activity_log')->whereIn('id', $request->ids)->update([
                'is_archive' => 1
            ]);
            return back()->with(['message' => __('words.selected-row-archived'), 'alert-type' => 'success']);
        }

        if (isset($request->ids) && isset($request->restore)) {
            DB::table('activity_log')->whereIn('id', $request->ids)->update([
                'is_archive' => 0
            ]);
            return back()->with(['message' => __('words.selected-row-restored'), 'alert-type' => 'success']);
        }

        if (isset($request->ids) && isset($request->forcDelete)) {
            Activity::whereIn('id', $request->ids)->delete();
            return back()->with(['message' => __('words.selected-row-force-deleted'), 'alert-type' => 'success']);
        }

        return back()->with(['message' => __('words.select-one-least'), 'alert-type' => 'error']);
    }

    public function restoreAllData()
    {
        DB::table('activity_log')->update([
            'is_archive' => 0
        ]);
        return back()->with(['message' => __('words.all-data-resotred-success'), 'alert-type' => 'success']);
    }
}
