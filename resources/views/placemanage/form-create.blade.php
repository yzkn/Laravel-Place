<table>
  <form action="/place" method="post">
    {{ csrf_field() }}
    <tr><th>desc: </th><td><input type="text" name="desc" value="{{old('desc')}}"></td></tr>
    <tr><th>owner: </th><td><input type="text" name="owner" value="{{old('owner')}}"></td></tr>
    <tr><th>lat: </th><td><input type="number" name="lat" value="{{old('lat')}}" step="0.000001"></td></tr>
    <tr><th>lng: </th><td><input type="number" name="lng" value="{{old('lng')}}" step="0.000001"></td></tr>
    <tr><th></th><td><input type="submit" value="send"></td></tr>
</form>
</table>
