<?php

namespace App\Http\Controllers\ViewControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Follow;
use App\Models\User;

class UsersListViewController extends Controller{

    public $user;


    public function __construct(){

        $this->middleware('auth');
        $this->user = Auth::user();
    }


    public function index(){

        // フォローしている人の名前込みの一覧
        $followed_list = $this->arrayFollowslist();
        $users = $this->queryUsers();

        \Log::debug($followed_list);

        return view("UsersListView", ['followed_list' => $followed_list, 'users'=> $users]);
    }


    public function follow(Request $request){

        $follow = new Follow;
        $follow->follow_user_id = Auth::user()['id'];
        $follow->followed_user_id = $request['user_id'];
        $follow->save();

        $followed_list = $this->arrayFollowslist();
        $users = $this->queryUsers();
        return view("UsersListView", ['users' => $users,'followed_list' => $followed_list,]);
    }

    public function unfollow(Request $request){

        //requestのidでfindして削除
        $unfollow = Follow::where("follow_user_id", Auth::user()->id)
            ->where("followed_user_id", $request["user_id"])->delete();
        //softdelete

        \Log::debug($unfollow);

        $followed_list = $this->arrayFollowslist();
        $users = $this->queryUsers();
        return view("UsersListView", ['users' => $users,'followed_list' => $followed_list,]);
    }




    function queryUsers(){
        $users = DB::table('users') // Collection型でstndClassのインスタンス
            ->select(["users.id","users.name","users.updated_at"])
            ->orderBy('users.updated_at', 'desc')
            ->get();

        $users = $users->map(function ($item){
            $item = array(
                "name" => $item->name,
                "updated_at" => $item->updated_at,
                "id"=> $item->id

            );

            return $item;//Array型
        }
        );

        return $users;
    }

    function arrayFollowslist(){
        $follows = DB::table("follows")->select(["followed_user_id", "follow_user_id","users.name as followed_name" ])
            ->join("users", "follows.followed_user_id","=", "users.id")
            ->where("follows.follow_user_id", Auth::user()->id)
            ->whereNull("users.deleted_at")
            ->orderBy('users.updated_at','desc')
            ->get();

        //ユーザー一覧　フォローしている人のユーザーidで取ってくる。

        $followed_list = array();
        foreach ($follows as $follow){
            $followed_list[$follow->followed_user_id] = $follow->followed_name;
        }

        //必要なものだけにする。

        return $followed_list; // Array

    }




}



