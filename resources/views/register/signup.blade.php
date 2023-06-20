@extends('layout.layout')
@section('content')
    <section class="login-box">
        @if(isset($error))
            <div class="login-error">
                <p class="login-error-p">
                    {{ $error[0] }}
                </p>
            </div>
        @endif
        <h3 class="login-box-h3">Sign up</h3>
        <form class="login-box-form" action="" method="POST">
            @csrf
            <label class="login-box-form-label-username" for="username">Username:</label>
            <input class="login-box-form-input-username" type="text" name="username" id="username"/>

            <label class="login-box-form-label-password" for="password">Password:</label>
            <input class="login-box-form-input-password" type="password" name="password" id="password"/>

            <label class="login-box-form-label-password" for="password_confirmation">Password Confirm:</label>
            <input class="login-box-form-input-password" type="password" name="password_confirmation" id="password_confirmation"/>

            <button class="login-box-form-submit" type="submit" name="submit-login">Sign up</button>
            <a class="login-box-form-anchor-signup" href="{{ url('/login') }}">
                <button class="login-box-form-signup-button" type="button" name="login-box-form-signup-button">Login</button>
            </a>
        </form>
    </section>
@endsection