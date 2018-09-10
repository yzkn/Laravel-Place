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
            </div>
        </div>
    </div>
    <div class="row justify-content-center mt-5 mb-5">
        <div class="col-md-8">
            <div class="card">
                <div class="mt-5 mb-5 ml-5 mr-5">
                    <form action="/where" method="post">
                        {{ method_field('POST') }}
                        {{ csrf_field() }}
                        <div class="input-group">
                            <input type="text" name="desc" class="form-control" placeholder="{{__('Description')}}" value="{{$desc}}">
                        </div>
                        <div class="input-group form-inline">
                            <input type="text" id="search_lat" name="lat" class="form-control" placeholder="{{__('Latitude')}}" value="{{$lat}}" />&nbsp;
                            <input type="text" id="search_lng" name="lng" class="form-control" placeholder="{{__('Longitude')}}" value="{{$lng}}" />
                        </div>
                        <div class="input-group">
                            <input type="submit" class="btn btn-secondary" value="{{__('Search')}}">
                            <input type="reset" class="btn btn-outline-danger" value="{{__('Reset')}}">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if (isset($items))

        @include('map.map-places', ['items'=>$items])

        <div class="row justify-content-center mt-5 mb-5">
            <div class="col-md-12">
                Results:
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
                                <th>{{ __('Distance') }}</th>
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
                                <td>{{ $item->lat }}</td>
                                <td>{{ $item->lng }}</td>
                                <td>{{ $item->created_at }}</td>
                                <td>{{ $item->updated_at }}</td>
                                <td>{{ $item->getUserName() }}</td>
                                <td>{{display_distance($item->dist)}}</td>
                                <td>
                                    <a href="{{ url('place/'.$item->id.'/edit') }}" class="btn btn-info">Edit</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                @if (NULL !== ($items->links()))
                {{ $items->appends(['desc' => $desc])->links() }}
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
