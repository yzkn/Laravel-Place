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

    @if (isset($items))
        @foreach ($items as $item)
            <form action="{{ url('user/'.$item->id) }}" method="post" id="form_put_{{ $item->id }}">
                {{ method_field('PUT') }} {{ csrf_field() }}
                <input type="hidden" name="id" value="{{ $item->id }}" form="form_put_{{ $item->id }}">
            </form>
            <form action="{{ url('user/'.$item->id) }}" method="post" id="form_delete_{{ $item->id }}">
                {{ method_field('DELETE') }} {{ csrf_field() }}
                <input type="hidden" name="id" value="{{$item->id}}" form="form_delete_{{ $item->id }}">
            </form>
        @endforeach
    @endif
    <form action="/user" method="post" id="form_post">
        {{ csrf_field() }}
        <input type="hidden" name="id" value="" form="form_post">
    </form>

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
                            <td>{{ $item->id }}</td>
                            <td>
                                <input type="text" id="name" name="name" placeholder="{{ __('Name')}}" value="{{ $item->name }}" class="form-control input-sm" form="form_put_{{ $item->id }}">
                            </td>
                            <td>
                                <input type="text" id="email" name="email" placeholder="{{ __('Email')}}" value="{{ $item->email }}" class="form-control input-sm" form="form_put_{{ $item->id }}">
                            </td>
                            <td>
                                <input type="text" id="password" name="password" placeholder="{{ __('Password')}}: {{ isset($item->password) ? 'set' : 'empty' }}" value="" class="form-control input-sm" form="form_put_{{ $item->id }}">
                            </td>
                            <td>
                                <!-- input type="text" id="role" name="role" placeholder="{{ __('Role')}}" value="{{ $item->role }}" class="form-control input-sm" form="form_put_{{ $item->id }}" -->
                                <select id="role" name="role" placeholder="{{ __('Role')}}" class="form-control custom-select custom-select-sm" form="form_put_{{ $item->id }}">
                                    <option value="">---</option>
                                    @foreach(config('auth.role_select') as $key => $val)
                                        <option value="{{$key}}" @if($item->role === $key) selected="selected" @endif>{{$val}}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>{{ $item->created_at }}</td>
                            <td><input type="submit" class="btn btn-primary" value="{{ __('Update') }}" form="form_put_{{ $item->id }}"></td>
                            <td><input type="submit" class="btn btn-danger" value="{{ __('Delete') }}" form="form_delete_{{ $item->id }}"></td>
                        </tr>
                    @endforeach
                        <tr>
                            <td></td>
                            <td>
                                <input type="text" id="name" name="name" placeholder="{{ __('Name')}}" class="form-control input-sm" form="form_post">
                            </td>
                            <td>
                                <input type="text" id="email" name="email" placeholder="{{ __('Email')}}" class="form-control input-sm" form="form_post">
                            </td>
                            <td>
                                <input type="text" id="password" name="password" placeholder="{{ __('Password')}}" class="form-control input-sm" form="form_post">
                            </td>
                            <td>
                                <!-- input type="text" id="role" name="role" placeholder="{{ __('Role')}}" class="form-control input-sm" form="form_post" -->
                                <select id="role" name="role" placeholder="{{ __('Role')}}" class="form-control custom-select custom-select-sm" form="form_put_{{ $item->id }}">
                                    <option value="">---</option>
                                    @foreach(config('auth.role_select') as $key => $val)
                                        @if($user->role === 1 || $key > 1)
                                            <option value="{{$key}}" @if(config('auth.default_value.role.editor') === $key) selected="selected" @endif>{{$val}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </td>
                            <td> </td>
                            <td>
                                <input type="submit" class="btn btn-primary" value="{{ __('Create') }}" form="form_post">
                            </td>
                            <td></td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
