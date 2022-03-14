<select name="{{$fieldName}}">
  <option value="NULL">tak/nie</option>
  @if($valueSelected == "tak")
    <option selected="selected" value="1">tak</option>
  @else
    <option value="1">tak</option>
  @endif
  @if($valueSelected == "nie")
    <option selected="selected" value="0">nie</option>
  @else
    <option value="0">nie</option>
  @endif
</select>