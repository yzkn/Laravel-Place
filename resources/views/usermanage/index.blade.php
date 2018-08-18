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
        <div class="col-md-12">
            Users:
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>{{ __('ID') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Email') }}</th>
                            <th>{{ __('Password') }}</th>
                            <th>{{ __('Role') }}</th>
                            <th>{{ __('CreatedAt') }}</th>
                            <th>{{ __('Update') }}</th>
                            <th>{{ __('Delete') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if (isset($items))
                    @foreach ($items as $item)
                        <tr>
                            <td>
                                <a href="{{ url('user/'.$item->id) }}">{{ $item->id }}</a>
                            </td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->email }}</td>
                            <td>{{ isset($item->password) ? 'set' : 'empty' }}</td>
                            <td>{{ $item->role }}</td>
                            <td>{{ $item->created_at }}</td>
                            <td>{{ __('Update') }}</td>
                            <td>{{ __('Delete') }}</td>
                        </tr>
                    @endforeach
                        <tr>
                            <form action="/user" method="post">
                                <td></td>
                                <td>
                                    <input type="text" id="name" name="name" placeholder="{{ __('Name')}}" class="form-control input-sm">
                                </td>
                                <td>
                                    <input type="text" id="email" name="email" placeholder="{{ __('Email')}}" class="form-control input-sm">
                                </td>
                                <td>
                                    <input type="text" id="password" name="password" placeholder="{{ __('Password')}}" class="form-control input-sm">
                                </td>
                                <td>
                                    <input type="text" id="role" name="role" placeholder="{{ __('Role')}}" class="form-control input-sm">
                                </td>
                                <td>
                                </td>
                                <td>
                                    <input type="submit" class="btn btn-default" value="{{ __('Create') }}">
                                </td>
                                <td>
                                </td>
                            </form>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
