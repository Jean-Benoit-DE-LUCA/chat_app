@extends('layout.layout')
@section('content')
    <section class="login-box">
        @if(isset($error))
            <div class="login-error">
                <p class="login-error-p">
                    {{ $error }}
                </p>
            </div>
        @endif
        <h3 class="login-box-h3">Login</h3>
        <form class="login-box-form" action="" method="POST">
            @csrf
            <label class="login-box-form-label-username" for="username">Username:</label>
            <input class="login-box-form-input-username" type="text" name="username" id="username"/>

            <label class="login-box-form-label-password" for="password">Password:</label>
            <input class="login-box-form-input-password" type="password" name="password" id="password"/>

            <button class="login-box-form-submit" type="submit" name="submit-login">Login</button>
            <a class="login-box-form-anchor-signup" href="{{ url('/signup') }}">
                <button class="login-box-form-signup-button" type="button" name="login-box-form-signup-button">Sign up</button>
            </a>
        </form>
    </section>
@endsection