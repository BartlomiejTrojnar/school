<select name="templates_id">
   <option value="0">- wybierz wzór -</option>
   @foreach($templates as $temp)
      <option value="{{ $temp->id }}">
        {{ $temp->name }}
      </option>
   @endforeach
</select>