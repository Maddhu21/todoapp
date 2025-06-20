<?php

namespace App\Http\Controllers;

use App\Models\RoleMaster;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthManager extends Controller
{
    function login()
    {
        return view('auth.login');
    }

    function loginPost(Request $request)
    {
        $request->validate([
            'email'     =>  'required',
            'password'  =>  'required'
        ]);

        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $role = RoleMaster::where('id', auth()->user()->role_master_id)->pluck('name')->first();
            session(['role' => $role]);
            return redirect()->intended(route("home"))
                ->with('toast', [
                    'type'      =>  'success',
                    'message'   =>  'Hello, ' . Auth()->user()->name,
                    'title'     =>  'Welcome'
                ]);
        }

        return redirect(route("login"))
            ->with('toast', [
                'type'      =>  'error',
                'message'   =>  'Invalid Email or Password',
                'title'     =>  'Fail'
            ]);
    }

    function register()
    {
        return view("auth.register");
    }

    function registerPost(Request $request)
    {
        $request->validate([
            'fullname'  =>  'required',
            'email'     =>  'required|email|unique:users',
            'password'  =>  'required|min:8|required_with:password2|same:password2',
            'password2' =>  'required|min:8'
        ]);

        $user = new User();
        $user->name = $request->fullname;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->role_master_id = "2";
        if ($user->save()) {
            return redirect(route("login"))
                ->with("success", "Account Has Been Created!");
        }
        return back()
            ->with('toast', [
                'type'      =>  'error',
                'message'   =>  'Please contact administrator',
                'title'     =>  'Fail'
            ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect(route("login"));
    }
}
