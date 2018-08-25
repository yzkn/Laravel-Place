@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="table-responsive">
            <p>
                @if (Auth::check())
                Hi,  {{$user->name}}!
                @else
                <a href="/register">{{__('Register')}}</a> | <a href="/login">Sign in</a>
                @endif
            </p>
            <br />
            <form role="form" method="post" action="/csv/export">
                {{ csrf_field() }}
                <input type="submit" value="Download">
            </form>
        </div>
    </div>
@endsection
