@php
    $title = __('Places');
@endphp
<html>
<head>
    <meta charset="utf-8" />
    <style>
        .pagination { font-size: small; }
        .pagination li { display:inline-block; }
    </style>
</head>
<body>
    <div class="container">
        <h1>{{ $title }}</h1>
        <div class="table-responsive">
            <p>
                @if (Auth::check())
                Hi,  {{$user->name}}!
                @else
                <a href="/register">{{__('Register')}}</a> | <a href="/login">Sign in</a>
                @endif
            </p>
            <br />
            Select your csv file.<br />
            <form role="form" method="post" action="/csv/import" enctype="multipart/form-data">
                {{ csrf_field() }}
                <input type="file" name="file">
                <input type="submit" value="Upload">
            </form>
        </div>
    </div>
</body>
</html>
