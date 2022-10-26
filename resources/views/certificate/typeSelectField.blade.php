<select name="type">
   <option value="0">- wybierz typ -</option>
   @foreach($types as $type)
      <option value="{{ $type }}">
        {{ $type }}
      </option>
   @endforeach
</select>