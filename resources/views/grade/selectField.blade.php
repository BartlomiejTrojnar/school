@if(empty( $year ))
   <select name="{{ $name }}">
      <option value="0">- wybierz klasę -</option>
      @foreach($grades as $grade)
         <option value="{{ $grade->id }}" @if($grade->id == $gradeSelected) selected="selected" @endif>
            {{ $grade->year_of_beginning }}-{{ $grade->year_of_graduation }} {{ $grade->symbol }}
         </option>
      @endforeach
   </select>
@else
   <select name="{{ $name }}" class="c">
      <option value="0">klasa</option>
      @foreach($grades as $grade)
         <option style="padding-left: 20px; color: red;" value="{{ $grade->id }}" @if($grade->id == $gradeSelected) selected="selected" @endif>
            {{ $year - $grade->year_of_beginning }}{{ $grade->symbol }}
         </option>
      @endforeach
   </select>
@endif