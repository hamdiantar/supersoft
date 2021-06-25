<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Users\CreateUserRequest;
use App\Http\Requests\Admin\Users\UpdateUserRequest;
use App\Models\Branch;
use App\Models\Shift;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Activitylog\Models\Activity;
use Spatie\Permission\Models\Role;


class UsersController extends Controller
{

    public function __construct()
    {
//        $this->middleware('permission:view_users');
//        $this->middleware('permission:create_users',['only'=>['create','store']]);
//        $this->middleware('permission:update_users',['only'=>['edit','update']]);
//        $this->middleware('permission:delete_users',['only'=>['delete','deleteSelected']]);
    }

    public function index(Request $request)
    {
        $requestWithArchive = $request->segment(4) === 'archive' ? true : false;
        if (!auth()->user()->can('view_users')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $users = User::query();

//        $users->branch();

        $ids = [];
        if ($request->has('name') && $request['name'] != '') {
            array_push($ids, $request['name']);
        }

        if ($request->has('email') && $request['email'] != '') {
            array_push($ids, $request['email']);
        }

        if ($request->has('phone') && $request['phone'] != '') {
            array_push($ids, $request['phone']);
        }

        if (false === empty($ids)) {
            $users->whereIn('id', $ids);
        }

        if ($request->has('branch_id') && $request['branch_id'] != '') {
            $users->where('branch_id', $request['branch_id']);
        }

        if ($request->has('shift_id') && $request['shift_id'] != '') {
            $users->whereHas('shifts', function ($q) use ($request) {
                $q->where('shift_id', $request['shift_id']);
            });
        }

        if ($request->has('active') && $request['active'] != '') {
            $users->where('status', 1);
        }

        if ($request->has('inactive') && $request['inactive'] != '') {
            $users->where('status', 0);
        }

        $shifts = filterSetting() ? Shift::all()->pluck('name', 'id') : null;
        $branches = filterSetting() ? Branch::all()->pluck('name', 'id') : null;
        $users_search = filterSetting() ? User::orderBy('id', 'ASC')->branch()->get() : null;
        $users = $users->get();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        if (!auth()->user()->can('create_users')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $branches = Branch::all()->pluck('name', 'id');
        $shifts = Shift::all()->pluck('name', 'id');

        $roles = 0;

        if (!authIsSuperAdmin()) {
            $auth = auth()->user();

            if ($auth->branch) {
                $branch = $auth->branch;
                $roles = $branch->roles()->orWhere('role_id', 1)->get();
            }
        }

        if (!$roles) {
            $roles = Role::get();
        }

        return view('admin.users.create', compact('roles', 'branches', 'shifts'));
    }

    public function store(CreateUserRequest $request)
    {
        $data = $request->only('name', 'email', 'phone', 'username', 'branch_id', 'is_admin_branch');

        $data['super_admin'] = 0;
        $data['status'] = 0;
        $data['is_admin_branch'] = 0;

        if ($request->has('is_admin_branch')) {
            $data['is_admin_branch'] = 1;
        }

        if ($request->has('super_admin')) {
            $data['super_admin'] = 1;
        }

        if ($request->has('email') && $request->email == null) {
            $data['email'] = ' ';
        }

        if ($request->has('phone') && $request->phone == null) {
            $data['phone'] = ' ';
        }

        if ($request->has('status')) {
            $data['status'] = 1;
        }

        $data['password'] = Hash::make($request['password']);

        if (!authIsSuperAdmin()) {
            $data['branch_id'] = auth()->user()->branch_id;
        }

        $user = User::create($data);

        if ($request->has('shifts')) {
            $user->shifts()->attach($request['shifts']);
        }

        if ($request->has('roles')) {
            $user->syncRoles($request['roles']);
        }

        return redirect(route('admin:users.index'))
            ->with(['message' => __('words.user-created'), 'alert-type' => 'success']);
    }

    public function show(User $user, Request $request)
    {
        if (!auth()->user()->can('view_users')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $loggedActivity = Activity::with('causer')->where('causer_id', $user->id);

        if ($request->has('model') && $request['model'] != '') {
            $loggedActivity->whereDate('subject_type', 'like', '%' . $request['model'] . '%');
        }

        if ($request->has('date_from') && $request['date_from'] != '') {
            $loggedActivity->whereDate('created_at', '>=', $request['date_from']);
        }

        if ($request->has('date_to') && $request['date_to'] != '') {
            $loggedActivity->where('created_at', '<=', $request['date_to']);
        }

        $loggedActivity = $loggedActivity->get();

        return view('admin.users.show', compact('user', 'loggedActivity'));
    }

    public function edit(User $user)
    {
        if (!auth()->user()->can('update_users')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $shifts = Shift::all()->pluck('name', 'id');
        $branches = Branch::all()->pluck('name', 'id');

        $roles = 0;

        if (!authIsSuperAdmin()) {
            $auth = auth()->user();

            if ($auth->branch) {
                $branch = $auth->branch;
                $roles = $branch->roles()->orWhere('role_id', 1)->get();
            }
        }

        if (!$roles) {
            $roles = Role::get();
        }

        return view('admin.users.edit', compact('user', 'roles', 'branches', 'shifts'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        if (!auth()->user()->can('update_users')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $data = $request->only('name', 'email', 'phone', 'username', 'branch_id', 'is_admin_branch');

        $data['super_admin'] = 0;
        $data['status'] = 0;
        $data['is_admin_branch'] = 0;

        if ($request->has('super_admin')) {
            $data['super_admin'] = 1;
        }

        if ($request->has('is_admin_branch')) {
            $data['is_admin_branch'] = 1;
        }
        if ($request->has('email') && $request->email == null) {
            $data['email'] = ' ';
        }

        if ($request->has('phone') && $request->phone == null) {
            $data['phone'] = ' ';
        }
        if ($request->has('status')) {
            $data['status'] = 1;
        }

        if ($request->has('password') && $request['password'] != null) {
            $data['password'] = Hash::make($request['password']);
        }

        if (!authIsSuperAdmin()) {
            $data['branch_id'] = auth()->user()->branch_id;
        }

        $user->update($data);

        if ($request->has('shifts')) {
            $user->shifts()->sync($request['shifts']);
        }

        if ($request->has('roles')) {
            $user->syncRoles($request['roles']);
        } else {
            $userRoles = $user->roles()->pluck('name');

            foreach ($userRoles as $key => $value) {
                $user->removeRole($value);
            }
        }

        return redirect(route('admin:users.index'))
            ->with(['message' => __('words.user-updated'), 'alert-type' => 'success']);
    }

    public function destroy(User $user)
    {
        if (!auth()->user()->can('delete_users')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        if ($user->id == 1 || $user->id == Auth::id()) {
            return redirect()->back()->with(['message' => __('words.cant-delete-admin'), 'alert-type' => 'error']);
        }

        if ($user->is_admin_branch) {
            return redirect()->back()->with(['message' => __('words.cant-delete-admin-branch'), 'alert-type' => 'error']);
        }

        $user->delete();
        return redirect()->back()->with(['message' => __('words.user-archived'), 'alert-type' => 'success']);
    }

    public function deleteSelected(Request $request)
    {
        if (!auth()->user()->can('delete_users')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        if (is_array($request->ids) && in_array(1, $request->ids)) {
            return redirect()->back()->with(['message' => __('words.cant-delete-admin'), 'alert-type' => 'error']);
        }

        if (isset($request->ids)) {
           foreach ($request->ids as $id) {
               $user = User::find($id);
               if ($user && $user->is_admin_branch) {
                   return redirect()->back()->with(['message' => __('words.cant-delete-admin-branch'), 'alert-type' => 'error']);
               }
           }
        }

        if (isset($request->ids)) {
            User::withTrashed()->whereIn('id', $request->ids)->forceDelete();
            return  back()->with(['message' => __('words.selected-row-force-deleted'), 'alert-type' => 'success']);
        }

        return  back()->with(['message' => __('words.select-one-least'), 'alert-type' => 'error']);
    }

    public function restoreDelete(int $id)
    {
        try {
            if ($id == 1 || $id == Auth::id()) {
                return redirect()->back()->with(['message' => __('words.cant-delete-admin'), 'alert-type' => 'error']);
            }
            $user = User::withTrashed()->findOrFail($id);
            $user->restore();
            return back()->with(['message' => __('words.data-restored-successfully'), 'alert-type' => 'success']);
        } catch (Exception $exception) {
            return back()->with(['message' => __('words.can-not-restore-date-from-archive'), 'alert-type' => 'error']);
        }
    }

    public function forceDelete(int $id)
    {
        try {
            if ($id == 1 || $id == Auth::id()) {
                return redirect()->back()->with(['message' => __('words.cant-delete-admin'), 'alert-type' => 'error']);
            }
            $user = User::withTrashed()->findOrFail($id);
            $user->forceDelete();
            return back()->with(['message' => __('words.data-forced-deleted-successfully'), 'alert-type' => 'success']);
        } catch (Exception $exception) {
            return back()->with(['message' => __('words.can-not-forced-deleted'), 'alert-type' => 'error']);
        }
    }

    public function deleteActivities(User $user)
    {
        try {
            $loggedActivity = Activity::with('causer')->where('causer_id', $user->id);
            foreach ($loggedActivity->get() as $activity) {
                $activity->delete();
            }
            foreach ($user->activities as $activity) {
                $activity->delete();
            }
            return back()->with(['message' => __('words.activities-deleted-successfully'), 'alert-type' => 'success']);
        } catch (Exception $exception) {
            return redirect()->back()->with(['message' => __('words.cant-delete-activities'), 'alert-type' => 'error']);
        }
    }
}
