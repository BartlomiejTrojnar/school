<select name="declaration_id">
   <option value="0">- wybierz deklarację -</option>
   @foreach($declarations as $declaration)
      @if($declaration->id == $declarationSelected)
         <option selected="selected" value="{{$declaration->id}}">
            {{$declaration->student->first_name}} {{$declaration->student->last_name}}
            {{$declaration->application_number}}
         </option>
      @else
         <option value="{{$declaration->id}}">
            {{$declaration->student->first_name}} {{$declaration->student->last_name}}
            {{$declaration->application_number}}
         </option>
      @endif
   @endforeach
</select>