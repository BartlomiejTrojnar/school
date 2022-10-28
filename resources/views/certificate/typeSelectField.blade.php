<select name="type">
   <option value="0">- wybierz typ -</option>
   @foreach($types as $type)
      @if($type == $typeSelected)
         <option selected="selected" value="{{ $type }}"> {{ $type }} </option>
      @else
         <option value="{{ $type }}"> {{ $type }} </option>
      @endif
   @endforeach
</select>