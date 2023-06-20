@extends('layout.layout')
@section('content')
@include('layout.header2')
        <div class="chat-room-box">
            <aside class="chat-room-aside">
                <h3 class="chat-room-h3">Users Online</h3>
                {{ Form::open(array('method' => 'POST')) }}
                    @csrf
                    <input type="hidden" name="userName" value="{{ $username }}" />
                {{ Form::close() }}
                <ul class="chat-room-ul">
                    <li class="chat-room-ul-li">{{ $username }}</li>
                </ul>
            </aside>
            <section class="chat-room-list">
                <h3 class="chat-room-h3">Rooms</h3>
                <ul class="chat-room-ul">
                    @foreach ($rooms as $room)
                        <a class="chat-room-ul-a-li" href="{{ url('/chat', $room->id) }}">
                            <li class="chat-room-ul-li room-num room-num{{ $room->id }}">{{ $room->id }}</li>
                        </a>
                    @endforeach
                </ul>
            </section>
        </div>
    </div>
</div>
@endsection