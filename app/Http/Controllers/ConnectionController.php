<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ConnectionController extends Controller
{   

    public function loginAction() {

        return view('connection/login');
    }

    public function logoutAction(Request $request) {

        $userName = session()->get('username');
        $deleteConnectedUser = DB::delete("DELETE FROM `users_connected` WHERE `users_connected`.`name` = ?", [htmlspecialchars($userName)]);
        
        Auth::logout();
        return redirect('/login');
    }

    public function loginPostAction(Request $request) {

        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required']
        ]);

        if (Auth::attempt($credentials)) {

            $request->session()->regenerate();

            $user = DB::table('users')->where('username', $request->input('username'))->first();
            session(['username' => $request->input('username')]);
            session(['userid' => $user->id]);

            $insertConnectedUser = DB::insert("INSERT INTO `users_connected` (name) VALUES (?)", [session()->get('username')]);

            return redirect()->intended('/');
        }

        else {

            return view('connection/login', ['error' => 'Login details are not valid']);
        }
    }
}
