<?php

namespace App\Http\Controllers\ViewControllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Tweet;
use Illuminate\Support\Facades\DB;


class AllTweetViewController extends Controller{
    public $user;

    public function __construct(){
        $this->middleware('auth');
        $this->user = Auth::user();
    }

    public function index(){

        $tweets = $this->queryForTweets();

        return view('AllTweetView', ['user'=> $this->user, 'tweets'=> $tweets]);
    }


    public  function post(Request $request){

        \Log::debug("request");
        \Log::debug($request);
        $validate_rule = [
            'tweet'=> 'required'
        ];
        $this->validate($request, $validate_rule);

        $tweet = new Tweet;
        $tweet->tweet = $request->tweet;
        $tweet->user_id =  Auth::user()->id;
        $tweet->save();

        $tweets = $this->queryForTweets();

        return view("AllTweetView", ['user' => $this->user ,'tweets'=> $tweets]);
    }



    function queryForTweets(){
        $tweets = DB::table('users')
            ->select(["users.name", "users.id", "users.created_at as users_created_at",
                "users.updated_at as users_updated_at", "tweets.tweet", "tweets.created_at as tweets_created_at",
                "tweets.updated_at as tweets_updated_at"])
            ->join('tweets', 'users.id', '=', 'tweets.user_id')
//            ->where("tweets.user_id", Auth::user()->id)
            ->orderBy('tweets.updated_at', 'desc')
            ->get();
        // Collection型でstndClassのインスタンス

        $tweets = $tweets->map(function ($item){
            $item = array(
                "name" => $item->name,
                "tweet" => $item->tweet,
                "updated_at" => $item->tweets_updated_at
            );

            return $item;
        }
        );

        return $tweets;
    }


}


//        $tweets = $tweets->map(function ($item) {
//            $item = collect($item)->forget('created_at')->forget('user_id');
//            return $item;
//        });