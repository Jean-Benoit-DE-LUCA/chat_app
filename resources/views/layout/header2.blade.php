<div class="chat-container">
    <div class="head-box-buttons">
        @if(Route::current()->getName() !== 'login')
            <a class="head-box-buttons-back" href="{{ url()->previous() }}"><img class="head-box-buttons-back-img" src="{{ asset('/assets/images/arrow-left.svg') }}" />Back</a>
        @endif
            <a class="head-box-buttons-logout" href="{{ url('/logout') }}">Logout</a>
    </div>
    <div class="chat-room-container">
        <h1 class="chat-room-h1">Chat</h1>