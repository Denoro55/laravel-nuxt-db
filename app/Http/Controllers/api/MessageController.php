<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{

    public function sendMessage(Request $request) {
        DB::table('messages')->insert(
            [
                'user_id' => $request->user_id,
                'companion_id' => $request->companion_id,
                'text' => $request->text,
                'chat_id' => $request->room_id
            ]
        );
    }

    public function getMessages(Request $request) {
        $messages = DB::select(DB::raw(
            "select m.text,
            case when u.id = {$request->user_id} then u2.image_url else u.image_url end as image_url,
            case when u.id = {$request->user_id} then u2.name else u.name end as name,
            case when u.id = {$request->user_id} then u2.id else u.id end as idd
            from messages m
            left join users u on u.id = m.user_id
            left join users u2 on u2.id = m.companion_id
            inner join (
              select 
                chat_id, max(id) as latest 
                from messages
                group by chat_id
              ) r
            on m.id = r.latest and m.chat_id = r.chat_id
            where (m.companion_id = {$request->user_id}) OR (m.user_id = {$request->user_id}) order by m.updated_at DESC"
        ));
        return $messages;
    }

    public function getUserMessages(Request $request) {

//        $companionExists = DB::table('users')->where('id', $request->companion_id)->exists();
//        if (!$companionExists) {
//            return [
//                'success' => false
//            ];
//        }

        $isFriend = DB::table('user_friends')
            ->where('user_id', $request->user_id)
            ->where('friend_id', $request->companion_id)
            ->where('status', 3)
            ->count();

        if (!$isFriend) {
            return [
                'success' => false
            ];
        }

        $messages = DB::select(DB::raw(
            "SELECT m.text, u.name, u.image_url, u.id as idd
             FROM messages m
             left join users u on u.id = m.user_id        
             WHERE (m.companion_id = {$request->user_id} AND m.user_id = {$request->companion_id})
             OR (m.companion_id = {$request->companion_id} AND m.user_id = {$request->user_id}) order by m.updated_at DESC"
        ));

        return [
            'success' => true,
            'messages' => $messages
        ];
    }
}
