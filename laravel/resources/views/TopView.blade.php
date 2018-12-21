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

<h1>Top Timeline </h1>
<form method="post" action="/">
    {{ csrf_field() }}
    <div>Tweet</div>
    <input type="text" name="tweet">
    <br>
    <input type="submit" value="Submit">
</form>
<br>

@foreach(range(0, $total_pages) as $page)
{{--buttonの動的な生成--}}
    <a href="/?page={{$page + 1}}"><button type="button">{{$page+1}}</button></a>
@endforeach

<br>
<br>

@foreach($tweets as $tweet)
    <h3> {{ $tweet["name"] }}:{{ $tweet["tweet"] }} {{ $tweet["updated_at"] }}
@endforeach

</body>
</html>