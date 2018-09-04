@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if (Auth::check())
                    You are logged in!
                    @endif
                </div>
                @if($isSysadmin==true)
                    <div class="card-body">
                        System admins only:
                        <a class="btn btn-secondary" href="/csv/import">
                            {{ __('Import') }}
                        </a>
                        <a class="btn btn-secondary" href="/csv/export">
                            {{ __('Export') }}
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @if (isset($user->places))

        @include('map.map-places', ['items'=>$user->places])

        <div class="row justify-content-center mt-5 mb-5">
            <div class="col-md-12">
                Items which you made:
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>{{ __('ID') }}</th>
                                <th>{{ __('Description') }}</th>
                                <th>{{ __('Owner') }}</th>
                                <th>{{ __('Latitude') }}</th>
                                <th>{{ __('Longitude') }}</th>
                                <th>{{ __('Created') }}</th>
                                <th>{{ __('Updated') }}</th>
                                <th>{{ __('User') }}</th>
                                <th>{{ __('Edit') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($user->places as $item)
                            <tr>
                                <td>
                                    <a href="{{ url('place/'.$item->id) }}">{{ $item->id }}</a>
                                </td>
                                <td>{{ $item->desc }}</td>
                                <td>{{ $item->owner }}</td>
                                <td>{{ $item->lat }}</td>
                                <td>{{ $item->lng }}</td>
                                <td>{{ $item->created_at }}</td>
                                <td>{{ $item->updated_at }}</td>
                                <td>{{ $item->getUserName() }}</td>
                                <td>
                                    <a href="{{ url('place/'.$item->id.'/edit') }}" class="btn btn-info">{{ __('Edit') }}</a>
                                </td>
                            </tr>
                        @endforeach
                            <tr>
                                <td> </td>
                                <td> </td>
                                <td> </td>
                                <td> </td>
                                <td> </td>
                                <td> </td>
                                <td> </td>
                                <td> </td>
                                <td>
                                    <a href="{{ url('place/create') }}" class="btn btn-info">{{ __('Create') }}</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                @if (NULL !== ($items->links()))
                {{ $items->links() }}
                @endif
            </div>
        </div>
    @else
        <div class="row justify-content-center">
            <div class="col-md-8">
                該当するデータが見つかりませんでした。
            </div>
        </div>
    @endif
</div>
@endsection
