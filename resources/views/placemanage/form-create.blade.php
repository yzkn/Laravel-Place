<table>
  <form action="/place" method="post">
    {{ csrf_field() }}
    <tr><th>desc: </th><td><input type="text" id="desc" name="desc" value="{{old('desc')}}">@if($errors->has('desc')){{implode($errors->get('desc'))}}@endif</td></tr>
    <tr><th>owner: </th><td><input type="text" id="owner" name="owner" value="{{old('owner')}}">@if($errors->has('owner')){{implode($errors->get('owner'))}}@endif</td></tr>
    <tr><th>lat: </th><td><input type="number" id="lat" name="lat" value="{{old('lat')}}" step="0.000001">@if($errors->has('lat')){{implode($errors->get('lat'))}}@endif</td></tr>
    <tr><th>lng: </th><td><input type="number" id="lng" name="lng" value="{{old('lng')}}" step="0.000001">@if($errors->has('lng')){{implode($errors->get('lng'))}}@endif</td></tr>
    <tr><th></th><td><input type="submit" value="send"></td></tr>
</form>
</table>
