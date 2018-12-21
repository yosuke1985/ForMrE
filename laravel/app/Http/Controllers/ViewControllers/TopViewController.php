<?php

namespace App\Http\Controllers\ViewControllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use App\Models\Tweet;
use App\Models\Follow;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Foreach_;

class TopViewController extends Controller{

    public $user;
    public $user_id;
    public $tweets;
    public $raw_tweets;
    public $followUserIDLists;
    public $pageNum;


    public function __construct(){
        $this->middleware('auth');
        $this->user = Auth::user();
        $this->tweets = array();
        $this->raw_tweets = array();
        $this->followUserIDLists = array();
        $this->pageNum = config('const.pageNum');
    }

    public function index(Request $request){
        $page = 1;
        $total_pages = $this->countPage(Auth::user()->id);

        //        \Log::debug($request);
//         1, $request->page　に値が存在するかどうか
//        \Log::debug(isset($request->page)); //1 or "
//        2, pageが整数かどうか。 validation Laravel p133

        \Log::debug($total_pages);
        if(isset($request->page) && is_integer((int)$request->page) === TRUE){
            $page = (int)$request->page;
        }

        $followList = $this->arrayFollowslist();// id=>nameのarray
        $this->timelineQuery($followList, Auth::user()->id, $page);

        return view('TopView', ['user'=>$this->user, 'tweets'=>$this->tweets, 'total_pages'=>$total_pages]);
    }


    public function post(Request $request){
        $page = 1;
        $validate_rule = [
            'tweet'=> 'required'
        ];
        $this->validate($request, $validate_rule);

        $tweet = new Tweet;
        $tweet->tweet = $request->tweet;
        $tweet->user_id =  Auth::user()->id;
        $tweet->save();

        $followList = $this->arrayFollowslist();// id=>nameのarray
        $this->timelineQuery($followList, Auth::user()->id, $page);


        return view("TopView", ['user' => $this->user,'tweets'=>$this->tweets]);
    }


    function arrayFollowslist(){

        $follows = DB::table("follows")->select(["followed_user_id", "follow_user_id","users.name as followed_name" ])
            ->join("users", "follows.followed_user_id","=", "users.id")
            ->where("follows.follow_user_id", Auth::user()->id)
            ->whereNull("users.deleted_at")
            ->get();
        $followed_list = array();
        foreach ($follows as $follow){
            $followed_list[$follow->followed_user_id] = $follow->followed_name;
        }


        return $followed_list; // Array

    }

    function timelineQuery(Array $followList, String $id, $page){
        \Log::debug('here is '.$page);
        $pages = ($page-1) * $this->pageNum;

        $tweet = DB::select("
            SELECT DISTINCT 
                users.id,
                users.name,
                tweets.id as tweet_id,
                tweets.tweet,
                tweets.updated_at,
            case when users.id = tweets.user_id  then 
            '自分のツイート'
            else 
            'フォローしている人のツイート' 
            end  as 'ツイートの分類',
                tweets.user_id AS 'フォローしている人のid',
                follow_users.name AS tweet_name
            FROM
                users
            
            INNER JOIN
                follows
            ON
                users.id =  follows.follow_user_id
            
            INNER JOIN
                tweets
            ON
                follows.followed_user_id = tweets.user_id
            OR
                tweets.user_id = {$id}
            
            INNER JOIN
                users follow_users
            ON
                follow_users.id = tweets.user_id
            WHERE
                users.id = {$id}
            
            ORDER BY 
                users.id, tweets.updated_at desc
            LIMIT
              {$pages}, $this->pageNum;
        ");

        $tweet = collect($tweet)->map(function ($item) {
            $item = array(
                "user_id" => $item->id,
                "name" => $item->tweet_name,
                "tweet" => $item->tweet,
                "updated_at" => $item->updated_at
            );

            return $item;
        })->toArray();

        $this->tweets = array_merge($this->tweets, $tweet);

        foreach($this->tweets as $key => $value){
            $sort_keys[$key] = $value['updated_at'];
        }

        array_multisort($sort_keys, SORT_DESC, $this->tweets);

    }


    function countPage(String $id){
        $countPages = 0;

        $tweetsCount = DB::select("
        SELECT 
            COUNT(DISTINCT tweets.id) as count
        FROM
            users
        
        INNER JOIN
            follows
        ON
            users.id =  follows.follow_user_id
        INNER JOIN
            tweets
        ON
            follows.followed_user_id = tweets.user_id
        OR
            tweets.user_id = {$id}
        
        INNER JOIN
            users follow_users
        ON
            follow_users.id = tweets.user_id
        WHERE
            users.id = {$id}
        ");

        $tweetsCount = collect($tweetsCount)->map(function ($item) {
            $item = array(
                "count" => $item->count,
            );

            return $item;
        })->toArray();

        $countPages = ceil($tweetsCount[0]['count'] / $this->pageNum) - 1;

        return $countPages;
    }

}

