<select name="{{ $name }}" class="c">
   <option value="0">- wybierz rok szkolny -</option>
   @foreach($schoolYears as $schoolYear)
      @if($schoolYear->id == $schoolYearSelected)
         <option selected="selected" value="{{$schoolYear->id}}">
            {{ substr($schoolYear->date_start, 0, 4) }}/{{ substr($schoolYear->date_end, 0, 4) }}
         </option>
      @else
         <option value="{{$schoolYear->id}}">
            {{ substr($schoolYear->date_start, 0, 4) }}/{{ substr($schoolYear->date_end, 0, 4) }}
         </option>
      @endif
   @endforeach
</select>