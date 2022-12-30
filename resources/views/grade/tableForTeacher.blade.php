<!-- **********************  (C) mgr inż. Bartłomiej Trojnar; 18.09.2021 ********************** -->
<table id="grades">
   <thead>
      <tr>
         <th>id</th>
         <?php
            echo view('layouts.thSorting', ["thName"=>"klasa", "routeName"=>"klasa.orderBy", "field"=>"year_of_beginning", "sessionVariable"=>"GradeOrderBy"]);
            echo view('layouts.thSorting', ["thName"=>"szkoła", "routeName"=>"klasa.orderBy", "field"=>"school_id", "sessionVariable"=>"GradeOrderBy"]);
         ?>
      </tr> 
      <tr>
         <td colspan="2"><?php  print_r($schoolYearSF);  ?></td>
         <td><?php  print_r($schoolSF);  ?></td>
      </tr>
   </thead>

   <tbody>
      <?php $count = 0; ?>
      @foreach($grades as $grade)
         <?php $count++; ?>
         <tr data-grade_id="{{$grade->id}}">
            <td>{{ $count }}</td>
            @if($year)
               <td><a href="{{ route('klasa.show', $grade->id) }}">{{ $year - $grade->year_of_beginning }}{{ $grade->symbol }}</a></td>
            @else
               <td><a href="{{ route('klasa.show', $grade->id) }}">{{ $grade->year_of_beginning }}-{{ $grade->year_of_graduation }} {{ $grade->symbol }}</a></td>
            @endif
            <td><a href="{{ route('szkola.show', $grade->school_id) }}">{{ $grade->school->name }}</a></td>
         </tr>
      @endforeach
   </tbody>
</table>
<input id="countGrades" type="hidden" value="{{ count($grades) }}" />