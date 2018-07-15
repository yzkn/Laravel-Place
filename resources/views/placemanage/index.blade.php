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
            <a href="{{ url('place/create') }}">Create</a>
            <hr />
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>{{ __('ID') }}</th>
                        <th>{{ __('Description') }}</th>
                        <th>{{ __('Owner') }}</th>
                        <th>{{ __('Creator') }}</th>
                        <th>{{ __('Latitude') }}</th>
                        <th>{{ __('Longitude') }}</th>
                        <th>{{ __('Created') }}</th>
                        <th>{{ __('Updated') }}</th>
                        <th>{{ __('Edit') }}</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($items as $item)
                    <tr>
                        <td>
                            <a href="{{ url('place/'.$item->id) }}">{{ $item->id }}</a>
                        </td>
                        <td>{{ $item->desc }}</td>
                        <td>{{ $item->owner }}</td>
                        <td>{{ $item->getUserName() }}</td>
                        <td>{{ $item->lat }}</td>
                        <td>{{ $item->lng }}</td>
                        <td>{{ $item->created_at }}</td>
                        <td>{{ $item->updated_at }}</td>
                        <td>
                            <a href="{{ url('place/'.$item->id.'/edit') }}">Edit</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $items->links() }}
        </div>
    </div>
</body>
</html>
