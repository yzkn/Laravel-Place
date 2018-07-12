<table>
  <form action="{{ url('place/'.$form->id) }}" method="post">
    <input type="hidden" name="id" value="{{$form->id}}">
    {{ method_field('PUT') }}
    {{ csrf_field() }}
    <tr><th>desc: </th><td><input type="text" name="desc" value="{{$form->desc}}"></td></tr>
    <tr><th>owner: </th><td><input type="text" name="owner" value="{{$form->owner}}"></td></tr>
    <tr><th>lat: </th><td><input type="number" name="lat" value="{{$form->lat}}" step="0.000001"></td></tr>
    <tr><th>lng: </th><td><input type="number" name="lng" value="{{$form->lng}}" step="0.000001"></td></tr>
    <tr><th></th><td><input type="submit" value="Update"></td></tr>
  </form>
  
  <form action="{{ url('place/'.$form->id) }}" method="post">
    <input type="hidden" name="id" value="{{$form->id}}">
    {{ method_field('DELETE') }}
    {{ csrf_field() }}
    <tr><th></th><td><input type="submit" value="Delete"></td></tr>
  </form>
</table>
