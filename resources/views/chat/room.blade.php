@extends('layout.layout')
@section('content')
@include('layout.header2')
<section class="chat-box-message">
    <h3 class="chat-room-title-number">Room n°{{ $idRoom }}</h3>
    <div class="chat-room-save-delete-box">
        <span class="chat-room-save-delete-span-one"></span>
        <span class="chat-room-save-delete-span-two"></span>
    </div>
        <div class="chat-room-save-delete-div">
            {{ Form::open(array('method' => 'POST', 'class' => 'save-form')) }}
                <label for="save-conversation">Save</label>
                <select class="save-conversation" id="save-conversation" name="save-conversation">
                    
                    @foreach ($getConversations as $id => $conversation)
                        <option value="{{ $conversation->id }}">{{ $conversation->id }}</option>
                    @endforeach

                </select>

                <input class="conversation-code" type="hidden" name="conversation-code" id="conversation-code" value="{{ $conversation_code }}"/>
                <button class="save-conversation-submit" type="submit" name="save-conversation-submit" id="save-conversation-submit">Save</button>
            {{ Form::close() }}
            {{ Form::open(array('method' => 'POST', 'class' => 'delete-form')) }}
                <label for="delete-conversation">Delete</label>
                <select class="delete-conversation" id="delete-conversation" name="delete-conversation">

                    @foreach ($getAllConversations as $conversation)
                        <option value="{{ $conversation->id }}">{{ $conversation->id }}</option>
                    @endforeach
                    
                </select>
                <button class="delete-conversation-submit" type="submit" name="delete-conversation-submit" id="delete-conversation-submit">Delete</button>
            {{ Form::close() }}
            {{ Form::open(array('method' => 'POST', 'class' => 'load-form')) }}
                <label for="load-conversation">Load</label>
                <select class="load-conversation" id="load-conversation" name="load-conversation">

                    @foreach ($getAllConversations as $conversation)
                        <option value="{{ $conversation->id }}">{{ $conversation->id }}</option>
                    @endforeach
                    
                </select>
                <button class="load-conversation-submit" type="submit" name="load-conversation-submit" id="load-conversation-submit">Load</button>
            {{ Form::close() }}
            
            <div class="display-status">
                <p class="display-status-message"></p>
            </div>
        </div>

    <div class="chat-room-message">

        @for ($i = 0; $i < 25; $i++)
            <hr class="chat-line-message-op">
            <span class="chat-span-message-wrap-op">
                <span class="chat-span-user-op"></span><span class="chat-span-message-op"></span>
            </span>
        @endfor
        
    </div>

    {{ Form::open(array('method' => 'POST', 'class' => 'chat-room-form')) }}
        <input class="chat-room-send-message" type="text" name="send-message" id="send-message" placeholder="Type text.."/>
        <input class="username-send" type="hidden" name="userName" id="username-send" value="{{ $username }}"/>
        <input class="userid-send" type="hidden" name="userid-send" id="userid-send" value="{{ $userid }}"/>
        <input class="conversation-code" type="hidden" name="conversation-code" id="conversation-code" value="{{ $conversation_code }}"/>
        <button class="chat-room-send-message-submit" type="submit" name="chat-room-send-message-submit" id="chat-room-send-message-submit">Send</button>
    {{ Form::close() }}
</section>
@endsection