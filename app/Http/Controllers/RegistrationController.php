<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\RegisterRequest;

use Redirect;

class RegistrationController extends Controller
{
    public static function signupAction() {

        return view('register/signup');
    }

    public function signupPostAction(Request $request) {

        $validator = Validator::make($request->all(), [
            'username' => 'required|unique:users,username',
            'password' => 'required|confirmed'
        ]);

        if ($validator->fails()) {

            $error = $validator->messages()->all();
            return view('register/signup', ['error' => $error]);
        }

        else {

            $user = User::create(request(['username', 'password']));
            auth()->login($user);

            $checkUser = DB::table('users')->where('username', $request->input('username'))->first();
            session(['username' => $request->input('username')]);
            session(['userid' => $checkUser->id]);

            $insertConnectedUser = DB::insert("INSERT INTO `users_connected` (name) VALUES (?)", [$request->input('username')]);

            return redirect()->to('/');
        }
    }
}
