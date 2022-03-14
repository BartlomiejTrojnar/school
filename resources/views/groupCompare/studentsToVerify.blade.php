<table>
  <thead>
    <tr>
      <th>id</th>
      <?php
        echo view('layouts.thSorting', ["thName"=>"imię", "routeName"=>"uczen.orderBy", "field"=>"first_name", "sessionVariable"=>"StudentOrderBy"]);
        echo view('layouts.thSorting', ["thName"=>"drugie imię", "routeName"=>"uczen.orderBy", "field"=>"second_name", "sessionVariable"=>"StudentOrderBy"]);
        echo view('layouts.thSorting', ["thName"=>"nazwisko", "routeName"=>"uczen.orderBy", "field"=>"last_name", "sessionVariable"=>"StudentOrderBy"]);
      ?>
    </tr>
  </thead>
</table>

<?php $year = substr($dateView, 0, 4); ?>
<ol>
  @foreach($students as $student)
    @if($student->sex == 'mężczyzna')
      <?php $sex="man" ?>
    @else
      <?php $sex="woman" ?>
    @endif
    <li class="{{$sex}}" data-student_id="{{ $student->id }}" data-groups_count="0">
      @foreach($student->grades as $sg)
        @if( $sg->date_start <= $dateView && $sg->date_end >= $dateView)
          {{$year - $sg->grade->year_of_beginning}}{{$sg->grade->symbol}}
          @if( !empty($student->numbers[0]->number) )
            {{$student->numbers[0]->number}}
          @endif
          <span class="studentName">{{ $student->first_name }} {{ $student->second_name }} {{ $student->last_name }}</span>
        @endif
      @endforeach
    </li>
  @endforeach
</ol>