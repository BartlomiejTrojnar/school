<select name="templates_id">
   <option value="0">- wybierz wzór -</option>
   @foreach($templates as $temp)
      @if($temp->id == $tempSelected)
         <option selected="selected" value="{{ $temp->id }}"> {{ $temp->name }} </option>
      @else
         <option value="{{ $temp->id }}"> {{ $temp->name }} </option>
      @endif
   @endforeach
</select>