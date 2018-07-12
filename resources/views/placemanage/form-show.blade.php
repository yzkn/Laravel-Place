<table>
  <form>
    {{ csrf_field() }}
    <tr><th>desc: </th><td><input type="text" name="desc" value="{{$form->desc}}" readonly="readonly" disabled="disabled"></td></tr>
    <tr><th>owner: </th><td><input type="text" name="owner" value="{{$form->owner}}" readonly="readonly" disabled="disabled"></td></tr>
    <tr><th>lat: </th><td><input type="number" name="lat" value="{{$form->lat}}" step="0.000001" readonly="readonly" disabled="disabled"></td></tr>
    <tr><th>lng: </th><td><input type="number" name="lng" value="{{$form->lng}}" step="0.000001" readonly="readonly" disabled="disabled"></td></tr>
  </form>
</table>
