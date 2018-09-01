<form>
    {{ csrf_field() }}
    <div class="form-group">
        <div class="input-group">
            <label>{{__('Description')}}: </label>
            <div class="input-group">
                <input type="text" id="desc" name="desc" value="{{$form->desc}}" class="form-control input-sm" readonly="readonly">
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="input-group">
            <label>{{__('Owner')}}: </label>
            <input type="text" id="owner" name="owner" value="{{$form->owner}}" class="form-control input-sm" readonly="readonly">
        </div>
    </div>
    <div class="form-group">
        <div class="input-group">
            <label>{{__('Latitude')}}: </label>
            <input type="number" id="lat" name="lat" value="{{$form->lat}}" min="-90" max="90" step="any" class="form-control input-sm"
                readonly="readonly">
        </div>
    </div>
    <div class="form-group">
        <div class="input-group">
            <label>{{__('Longitude')}}: </label>
            <input type="number" id="lng" name="lng" value="{{$form->lng}}" min="-180" max="180" step="any" class="form-control input-sm"
                readonly="readonly">
        </div>
    </div>
    <div class="form-group">
        <label>{{__('Image')}}: </label>
        @if(isset($form->image))
            <img src="{{ asset('storage/'.config('file.path').'/'.$form->image) }}" class="img-thumbnail">
        @endif
    </div>
</form>

<form action="{{ url('place/'.$form->id) }}" method="post">
    <div class="form-group">
        <input type="hidden" name="id" value="{{$form->id}}"> {{ method_field('DELETE') }} {{ csrf_field() }}
        <input type="submit" class="btn btn-danger" value="Delete">
    </div>
</form>
