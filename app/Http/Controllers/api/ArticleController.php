<?php

namespace App\Http\Controllers\api;

use App\Article;
use App\ArticleComments;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\JWTAuth;

class ArticleController extends Controller
{

    protected $auth;

    public function __construct(JWTAuth $auth)
    {
        $this->auth = $auth;
    }

    public function like(Request $request)
    {
        $type = $request->type;
        if ($type === 'add') {
            DB::table('article_likes')->insert(
                ['user_id' => $request->user_id, 'article_id' => $request->article_id]
            );
        } else {
            DB::table('article_likes')->where('user_id', $request->user_id)->where('article_id', $request->article_id)->delete();
        }
    }

    public function commentsAll(Request $request)
    {
//        return ArticleComments::where('article_id', $request->article_id)
//            ->orderBy('created_at', 'desc')
//            ->get();
        return DB::select(DB::raw("SELECT ac.*, u.image_url as userImage 
            from article_comments ac
            left join users u ON u.id = ac.user_id
            where ac.article_id = {$request->article_id} order by ac.created_at DESC"));
    }

    public function comment(Request $request) // create new article
    {
        $comment = new ArticleComments();
        $comment->fill($request->all());
        $comment->save();
        return [
            'success' => true
        ];
    }

    public function index(Request $request)
    {
//        $articles = DB::select(DB::raw("
//            SELECT a.*, COUNT(al.article_id) as likes, COUNT(u.id) as liked, COUNT(ac.article_id) as commentsCount
//            from articles a
//            left join article_likes al ON a.id = al.article_id
//            left join article_comments ac ON a.id = ac.article_id
//            left join users u ON al.user_id = u.id AND u.id = {$request->user_id}
//            where a.user_id = {$request->user_id} group by a.id order by a.updated_at"));

        $articles = DB::select(DB::raw(
            "SELECT a.*,
            (SELECT COUNT(1) FROM article_likes al WHERE al.article_id = a.id) as likes,
            (SELECT COUNT(1) FROM article_comments ac WHERE ac.article_id = a.id) as commentsCount,
            (SELECT COUNT(1) FROM article_likes al WHERE al.article_id = a.id AND al.user_id = {$request->user_id}) as liked
            from articles a
            where a.user_id = {$request->user_id} group by a.id order by a.updated_at"));

        return $articles;
    }

    public function store(Request $request) // create new article
    {
        $article = new Article();
        $article->title = $request->title;
        $article->content = $request->content;
        $article->user_id = $request->user_id;
        $article->save();
        return [
            'success' => true
        ];
    }

    public function remove(Request $request) // create new article
    {
        $article = Article::findOrFail($request->article_id);
        $article->delete();
        DB::table('article_likes')->where('article_id', $request->article_id)->delete();
        return [
            'success' => true
        ];
    }
}
