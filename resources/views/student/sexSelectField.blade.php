<select name="sex">
  <option value="0">- wybierz płeć -</option>
  @if($sex == 'kobieta')
    <option selected="selected" value="kobieta">kobieta</option>
    <option value="mężczyzna">mężczyzna</option>
  @endif
  @if($sex == 'mężczyzna')
    <option value="kobieta">kobieta</option>
    <option selected="selected" value="mężczyzna">mężczyzna</option>
  @endif
</select>