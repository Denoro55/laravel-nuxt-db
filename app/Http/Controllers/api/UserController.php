<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(Request $request) {
//        $user = User::where('id', $request->user_id)->first();
        $user = DB::select(DB::raw(
            "SELECT u.*,
             (SELECT COUNT(1) FROM avatar_likes al WHERE al.user_id = u.id) as avatarLikes,
             (SELECT COUNT(1) FROM user_friends uf WHERE uf.user_id = u.id AND uf.status = 3) as userFriends,
             (SELECT COUNT(1) FROM avatar_likes al WHERE al.user_id = u.id AND al.liker_id = {$request->watcher_id}) as liked,
             (SELECT status FROM user_friends uf WHERE uf.user_id = {$request->watcher_id} 
             AND uf.friend_id = {$request->user_id}) as isFriend
             FROM users u WHERE u.id = {$request->user_id}"
        ));
        $articles = DB::select(DB::raw(
            "SELECT a.*,
            (SELECT COUNT(1) FROM article_likes al WHERE al.article_id = a.id) as likes,
            (SELECT COUNT(1) FROM article_comments ac WHERE ac.article_id = a.id) as commentsCount,
            (SELECT COUNT(1) FROM article_likes al WHERE al.article_id = a.id AND al.user_id = {$request->watcher_id}) as liked
            from articles a
            where a.user_id = {$request->user_id} group by a.id order by a.updated_at"));
        $data = [
            'user' => $user,
            'articles' => $articles
        ];
        return $data;
    }

    public function profile(Request $request) {
        $data = DB::select(DB::raw(
            "SELECT u.name,
            (SELECT COUNT(1) FROM avatar_likes al WHERE al.user_id = u.id) as likes
            from users u
            where u.id = {$request->user_id}"));
        return $data;
    }

    public function getFriends(Request $request) {
        $data = DB::select(DB::raw(
            "SELECT u.name, u.image_url, u.status, u.id
            from user_friends uf
            left join users u on uf.friend_id = u.id 
            WHERE uf.user_id = {$request->user_id} AND uf.status = 3"));
        return $data;
    }

    public function getFriendRequests(Request $request) {
        $data = DB::select(DB::raw(
            "SELECT u.name, u.image_url, u.status, u.id
            from user_friends uf
            left join users u on uf.friend_id = u.id 
            WHERE uf.user_id = {$request->user_id} AND uf.status = 2"));
        return $data;
    }

    public function confirmFriend(Request $request) {
//        DB::table('user_friends')
//            ->where('id', $request->user_id)
//            ->update(['status' => 2]);
//        DB::table('user_friends')
//            ->where('id', $request->friend_id)
//            ->update(['status' => 2]);
        DB::table('user_friends')
            ->whereIn('user_id', [$request->user_id, $request->friend_id])
            ->update(['status' => 3]);
        return [
            'success' => true
        ];
    }

    public function likeAvatar(Request $request) {
        if ($request->type === 'like') {
            DB::table('avatar_likes')->insert(
                ['user_id' => $request->user_id, 'liker_id' => $request->liker_id]
            );
        } else {
            DB::table('avatar_likes')->where('user_id', $request->user_id)->where('liker_id', $request->liker_id)->delete();
        }
    }

    public function addFriend(Request $request) {
        DB::table('user_friends')->insert([
            ['user_id' => $request->user_id, 'friend_id' => $request->friend_id, 'status' => 1],
            ['user_id' => $request->friend_id, 'friend_id' => $request->user_id, 'status' => 2]
        ]);
    }

    public function removeFriend(Request $request) {
        DB::table('user_friends')->where('user_id', $request->user_id)->where('friend_id', $request->friend_id)->delete();
        DB::table('user_friends')->where('user_id', $request->friend_id)->where('friend_id', $request->user_id)->delete();
    }

    public function updateAvatar(Request $request) {
        $user = DB::table('users')->select('name')->where('id', $request->user_id)->first();
        $file = $request->file('file')->store("uploads/{$user->name}", 'public');
        DB::table('users')
            ->where('id', $request->user_id)
            ->update(['image_url' => $file]);
        return $file;
//        return $request->file->getClientMimeType();
//        $content = Storage::disk('csv')->get('file.csv');
    }
}
