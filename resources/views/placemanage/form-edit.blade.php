<form action="{{ url('place/'.$form->id) }}" method="post" enctype="multipart/form-data">
    <input type="hidden" name="id" value="{{$form->id}}"> {{ method_field('PUT') }} {{ csrf_field() }}
    <div class="form-group">
        <div class="input-group">
            <label>{{__('Description')}}: </label>
            <div class="input-group">
                <input type="text" id="desc" name="desc" value="{{$form->desc}}" class="form-control input-sm">
            </div>
            @if($errors->has('desc')){{implode($errors->get('desc'))}}@endif
        </div>
    </div>
    <div class="form-group">
        <div class="input-group">
            <label>{{__('Owner')}}: </label>
            <input type="text" id="owner" name="owner" value="{{$form->owner}}" class="form-control input-sm">
        </div>
        @if($errors->has('owner')){{implode($errors->get('owner'))}}@endif
    </div>
    <div class="form-group">
        <div class="input-group">
            <label>{{__('Latitude')}}: </label>
            <input type="number" id="lat" name="lat" value="{{$form->lat}}" min="-90" max="90" step="any" class="form-control input-sm">
        </div>
        @if($errors->has('lat')){{implode($errors->get('lat'))}}@endif
    </div>
    <div class="form-group">
        <div class="input-group">
            <label>{{__('Longitude')}}: </label>
            <input type="number" id="lng" name="lng" value="{{$form->lng}}" min="-180" max="180" step="any" class="form-control input-sm">
        </div>
        @if($errors->has('lng')){{implode($errors->get('lng'))}}@endif
    </div>
    <div class="form-group">
        <label>{{__('Image')}}: </label>
        <img src="{{ url('/') }}{{$form->image}}">
        <input type="file" name="image" class="form-control{{ $errors->has('image') ? ' is-invalid' : '' }}" placeholder="画像ファイル">
        @if ($errors->has('image'))
            <span class="invalid-feedback">
                <strong>{{ $errors->first('image') }}</strong>
            </span>
        @endif
    </div>
    <div class="form-group">
        <input type="submit" class="btn btn-primary" value="Update">
        <input type="submit" class="btn btn-danger" value="Delete" form="form_delete">
    </div>
</form>

<form action="{{ url('place/'.$form->id) }}" method="post" id="form_delete">
    <input type="hidden" name="id" value="{{$form->id}}"> {{ method_field('DELETE') }} {{ csrf_field() }}
</form>
