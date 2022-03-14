@if(empty( $year ))
   <select name="{{$name}}">
      <option value="0">- wybierz klasę -</option>
      @foreach($grades as $grade)
         @if($grade->id == $gradeSelected)
            <option selected="selected" value="{{$grade->id}}">
               {{$grade->year_of_beginning}}-{{$grade->year_of_graduation}} {{$grade->symbol}}
            </option>
         @else
            <option value="{{$grade->id}}">
               {{$grade->year_of_beginning}}-{{$grade->year_of_graduation}} {{$grade->symbol}}
            </option>
         @endif
      @endforeach
   </select>
@else
   <select name="{{$name}}">
      <option value="0">klasa</option>
      @foreach($grades as $grade)
         @if($grade->id == $gradeSelected)
            <option selected="selected" value="{{$grade->id}}">
               {{$year - $grade->year_of_beginning}}{{$grade->symbol}}
            </option>
         @else
            <option value="{{$grade->id}}">
               {{$year-$grade->year_of_beginning}}{{$grade->symbol}}
            </option>
         @endif
      @endforeach
   </select>
@endif