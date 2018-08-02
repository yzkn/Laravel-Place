<table>
  <form action="{{ url('place/'.$form->id) }}" method="post">
    <input type="hidden" name="id" value="{{$form->id}}">
    {{ method_field('PUT') }}
    {{ csrf_field() }}
    <tr><th>desc: </th><td><input type="text" id="desc" name="desc" value="{{$form->desc}}">@if($errors->has('desc')){{implode($errors->get('desc'))}}@endif</td></tr>
    <tr><th>owner: </th><td><input type="text" id="owner" name="owner" value="{{$form->owner}}">@if($errors->has('owner')){{implode($errors->get('owner'))}}@endif</td></tr>
    <tr><th>lat: </th><td><input type="number" id="lat" name="lat" value="{{$form->lat}}" step="0.000001">@if($errors->has('lat')){{implode($errors->get('lat'))}}@endif</td></tr>
    <tr><th>lng: </th><td><input type="number" id="lng" name="lng" value="{{$form->lng}}" step="0.000001">@if($errors->has('lng')){{implode($errors->get('lng'))}}@endif</td></tr>
    <tr><th></th><td><input type="submit" value="Update"></td></tr>
  </form>

  <form action="{{ url('place/'.$form->id) }}" method="post">
    <input type="hidden" name="id" value="{{$form->id}}">
    {{ method_field('DELETE') }}
    {{ csrf_field() }}
    <tr><th></th><td><input type="submit" value="Delete"></td></tr>
  </form>
</table>
