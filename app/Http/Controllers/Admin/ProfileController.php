<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Users\UpdateUserRequest;
use App\Http\Requests\ProfileRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('admin.profile.index', compact('user'));
    }

    public function update(ProfileRequest $request, User $user)
    {
        $data = $request->only('name','email','phone','username');

        if($request->has('password') && $request['password'] != null) {
            $data['password'] = Hash::make($request['password']);
        }

        if ($request->has('image') && $request->file('image') !== null) {
            $data['image'] = uploadImage($request->file('image'), 'profiles');
        }

        $user->update($data);

        return back()->with(['message'=>  __('words.user-updated'),'alert-type'=>'success']);
    }
}
