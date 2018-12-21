<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<body>
@extends('layouts.app')

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


<h1>All Tweets</h1>
    <form method="post" action="/AllTweet">
        {{ csrf_field() }}
        <div>Tweet</div>
        <input type="text" name="tweet">
        <br>
        <input type="submit" value="Submit">
    </form>


@foreach($tweets as $tweet)
    <h3>{{ $tweet["name"] }}: {{ $tweet["tweet"] }} {{ $tweet["updated_at"] }}
@endforeach

</body>
</html>