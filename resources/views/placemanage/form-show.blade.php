<table>
  <form>
    {{ csrf_field() }}
    <tr><th>desc: </th><td><input type="text" id="desc" name="desc" value="{{$form->desc}}" readonly="readonly"></td></tr>
    <tr><th>owner: </th><td><input type="text" id="owner" name="owner" value="{{$form->owner}}" readonly="readonly"></td></tr>
    <tr><th>lat: </th><td><input type="number" id="lat" name="lat" value="{{$form->lat}}" min="-90" max="90" step="any" readonly="readonly"></td></tr>
    <tr><th>lng: </th><td><input type="number" id="lng" name="lng" value="{{$form->lng}}" min="-180" max="180" step="any" readonly="readonly"></td></tr>
  </form>
</table>
