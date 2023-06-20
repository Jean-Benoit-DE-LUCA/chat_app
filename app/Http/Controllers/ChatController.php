<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Message;

class ChatController extends Controller
{
    
    public function chatAction(Request $request) {

        $rooms = DB::table('rooms')->get();

        return view('chat/chat', ['rooms' => $rooms, 'username' => session()->get('username')]);
    }

    public function chatRoomAction($id) {

        $generateCode = substr(md5(uniqid()), 0, 12);

        $getConversations = DB::select("SELECT * FROM `conversations` WHERE `conversations`.`id` NOT IN (SELECT DISTINCT `conversations`.`id` FROM `conversations` INNER JOIN `includes` ON `includes`.`conversation_id` = `conversations`.`id` WHERE `includes`.`user_id` = ?)", [session()->get('userid')]);

        $getAllConversations = DB::select("SELECT DISTINCT `conversations`.`id` FROM `conversations` INNER JOIN `includes` ON `includes`.`conversation_id` = `conversations`.`id` WHERE `includes`.`user_id` = ?", [session()->get('userid')]);

        return view('chat/room', [
            'idRoom' => $id, 
            'username' => session()->get('username'), 
            'userid' => session()->get('userid'), 
            'conversation_code' => $generateCode, 
            'getConversations' => $getConversations,
            'getAllConversations' => $getAllConversations
        ]);
    }

    public function chatRoomPostAction(Request $request, $id) {

        if ($request->has('saveConversationSubmit')) {

            $userId = $request->input('userId');
            $saveConversation = $request->input('saveConversation');
            $uniqueConversationsCode = $request->input('uniqueConversationsCode');

            $getAllMessages = [];
            foreach($uniqueConversationsCode as $code) {

                $getAllMessages[] = DB::select("SELECT * FROM `messages` WHERE `messages`.`conversation_code` = ?", [$code]);
            }

            $getAllIdMessages = [];
            foreach($getAllMessages as $messages) {

                foreach($messages as $message) {

                    $getAllIdMessages[] = $message->id;
                }
            }

            foreach($getAllIdMessages as $idMessage) {

                DB::insert("INSERT INTO `includes` (message_id, conversation_id, user_id) VALUES (?, ?, ?)", [$idMessage, $saveConversation, $userId]);
            }
        }

        if ($request->has('chatRoomSendMessageSubmit')) {

            $inputMessage = htmlspecialchars($request->input('inputMessage'));
            $idRoom = $request->input('idRoom');
            $userId = $request->input('userId');
            $conversationCode = $request->input('conversationCode');

            $insertMessage = DB::insert('INSERT INTO `messages` (message, room_id, user_id, conversation_code) VALUES (?, ?, ?, ?)', [$inputMessage, $idRoom, $userId, $conversationCode]);
        }
    }

    public function chatRoomGetLoadAction(Request $request, $id, $loadNumber) {

        $getMessagesLoad = DB::select("SELECT `messages`.*, `users`.`username` FROM `messages`
         INNER JOIN `includes` ON `includes`.`message_id` = `messages`.`id` 
         INNER JOIN `users` ON `users`.`id` = `messages`.`user_id` 
         WHERE `includes`.`user_id` = ? AND `includes`.`conversation_id` = ?", [session()->get('userid'), $loadNumber]);

        $getMessagesToLoadFormat = [];

        foreach($getMessagesLoad as $message) {

            $getMessagesToLoadFormat[$message->id] = [

                "message" => htmlspecialchars_decode($message->message),
                "room_id" => $message->room_id,
                "user_id" => $message->user_id,
                "conversation_code" => $message->conversation_code,
                "created_at" => $message->created_at,
                "updated_at" => $message->updated_at,
                "username" => $message->username
            ];
        }

        return json_encode($getMessagesToLoadFormat);

    }

    public function chatRoomDeleteAction(Request $request, $id, $deleteNumber) {

        DB::delete("DELETE FROM `includes` WHERE `includes`.`conversation_id` = ? AND `includes`.`user_id` = ?", [$deleteNumber, session()->get('userid')]);

    }

    public function chatGetAction(Request $request) {

        $getConnectedUsers = DB::select("SELECT * FROM `users_connected`");
        
        $getConnectedUsersFormat = [];

        foreach($getConnectedUsers as $user) {

            $getConnectedUsersFormat[$user->id] = [

                "name" => $user->name,
                "created_at" => $user->created_at,
                "updated_at" => $user->updated_at
            ];
        }

        return json_encode($getConnectedUsersFormat);
    }
}
