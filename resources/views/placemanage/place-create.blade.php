@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center mt-5 mb-5">
        <div class="col-md-5">
            @include('placemanage.form-create')
        </div>
    </div>
    @include('map.map-part')
</div>
@endsection
