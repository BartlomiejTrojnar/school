<select name="{{ $name }}">
   <option value="0">- wybierz grupę -</option>
   @foreach($groups as $group)
      @if($group->id == $groupSelected)
         <option selected="selected" value="{{ $group->id }}">
            {{ $year - $group->grades[0]->grade->year_of_beginning }}
            @foreach($group->grades as $groupGrade){{ $groupGrade->grade->symbol }}@endforeach  
            {{ $group->subject->name }} {{ $group->date_start }}
         </option>
      @else
         <option value="{{ $group->id }}">
         <?php /*
         {{ $year - $group->grades[0]->grade->year_of_beginning }}
         */ ?>
         @foreach($group->grades as $groupGrade){{$groupGrade->grade->symbol}}@endforeach  
         {{ $group->subject->name }} {{ $group->comments }} {{ $group->date_start }}
       </option>
     @endif
   @endforeach
</select>