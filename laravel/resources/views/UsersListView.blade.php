<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@extends('layouts.app')
<body>

<!-- Authentication Links -->
@guest
    <li>
        <a href="{{ route('login') }}">{{ __('Login') }}</a>
    </li>
    <li >
        @if (Route::has('register'))
            <a href="{{ route('register') }}">{{ __('Register') }}</a>
        @endif
    </li>
@else
    <b>Tweet</b>
    <li>
        <a href="/AllTweet">All Tweets</a>
    </li>
    <li>
        <a href="/">
            {{ Auth::user()->name }}'s Timeline
        </a>
    </li>
    <br>
    <b>Follow</b>
    <li>
        <a href="/users">User List</a>
    </li>

    <br>
    <b>Logout</b>
    <li>
        <a href="{{ route('logout') }}" onclick="event.preventDefault();
        document.getElementById('logout-form').submit();">
            {{ __('Logout') }}
        </a>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
        </form>
    </li>
    <br>
@endguest

{{--$followed_list--}}
{{--$users--}}


@foreach($users as $user)
    {{--if arrayのkey に存在しているか　$user["id"]--}}
    @if (!array_key_exists( $user["id"], $followed_list))

        {{--echo "Follow"--}}
        {{--// Follow--}}
        <form method="post" action = "/users/follow">
            {{ csrf_field() }}
            <input type="hidden" name = "user_id" value = {{ $user["id"] }} />
            <input type="submit" name = "Follow" value="Follow"  /> {{ $user["name"] }}: {{ $user["updated_at"] }}
        </form>

    @else
        {{--// Followed--}}
        {{--echo "Followed"--}}
        <form method="post" action = "/users/unfollow">
        {{ csrf_field() }}
        <input type="hidden" name = "user_id" value = {{ $user["id"] }} />
        <input type="submit" name = "Follow" value="Followed"  /> {{ $user["name"] }}: {{ $user["updated_at"] }}
        </form>

    @endif
@endforeach







</body>
</html>


{{--@foreach($users as $user)--}}
    {{--<form method="post" action = "/users/unfollow">--}}
        {{--{{ csrf_field() }}--}}
        {{--<input type="hidden" name = "user_id" value = {{ $user["id"] }} />--}}
        {{--<input type="submit" name = "Follow" value="Followed"  /> {{ $user["name"] }}: {{ $user["updated_at"] }}--}}
    {{--</form>--}}

{{--@endforeach--}}