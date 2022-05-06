<section id="studentGrades">
<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 06.05.2022 *********************** -->
   <h2>uczniowe w klasie</h2>
   @if( !empty($grade) )
      <div id="gradeButtons" class="c">
         <?php
            for($i=$grade->year_of_graduation-1; $i>=$grade->year_of_beginning; $i--)
            printf('<button class="btn btn-primary" data-year="%s" data-study-year="%s" data-school-year="%s">klasa %s</button>', $grade->year_of_beginning+($grade->year_of_graduation-$i), $grade->year_of_graduation-$i, $schoolYearEnd+$grade->year_of_beginning-$i, $grade->year_of_graduation-$i);
         ?>
         <button class="btn btn-primary" data-study-year="0">wszystkie</button>
      </div>
      <div class="form-inline c">
         <label for="start">data początkowa</label>
         <input id="start" type="date" class="form-control" name="start" placeholder="2018-08-31" size="12" value="{{ $start }}">
         <label for="end">data końcowa</label>
         <input id="end" type="date" class="form-control" name="end" placeholder="2018-08-31" value="{{ $end }}">
         <label for="schoolYear">rok szkolny</label>
         <input id="schoolYear" type="text" class="form-control" name="schoolYear" value="{{1900+$schoolYearEnd}}" size="3" disabled>
      </div>
   @endif

   <table>
      <tr>
         <th>lp</th>
         <th>nr księgi</th>
         <?php
            echo view('layouts.thSorting', ["thName"=>"uczeń", "routeName"=>"klasy_ucznia.orderBy", "field"=>"last_name", "sessionVariable"=>"StudentGradeOrderBy"]);
            echo '<th>numer</th>';
            echo view('layouts.thSorting', ["thName"=>"od", "routeName"=>"klasy_ucznia.orderBy", "field"=>"start", "sessionVariable"=>"StudentGradeOrderBy"]);
            echo view('layouts.thSorting', ["thName"=>"do", "routeName"=>"klasy_ucznia.orderBy", "field"=>"end", "sessionVariable"=>"StudentGradeOrderBy"]);
         ?>
         <th>zmień / usuń</th>
      </tr>

      <?php $count = 0; ?>
      @foreach($studentGrades as $sg)
         <!-- otwarcie wiersza dla ucznia -->
         @if($sg->student->sex == 'mężczyzna')
           <tr class="man" data-start="{{ $sg->start }}" data-end="{{ $sg->end }}" data-student_grade_id="{{ $sg->id }}">
         @else
           <tr class="woman" data-start="{{ $sg->start }}" data-end="{{ $sg->end }}" data-student_grade_id="{{ $sg->id }}">
         @endif

            <td>{{ ++$count }}</td>
            <!-- numer z księgi ucznia -->
            <td>
               @foreach($sg->student->bookOfStudents as $book)
                  @if( $sg->grade->school_id == $book->school_id )
                     {{ $book->number }}
                  @endif
               @endforeach
               @if(count($sg->student->bookOfStudents)==0)
                  <a href="{{ route('ksiega_uczniow.create', "version=createForm&student_id=".$sg->student->id) }}"><i class="fas fa-plus"></i></a>
                  <button class="showCreateForm" data-student_id="{{$sg->student->id}}" data-studentGradeId="{{ $sg->id }}">dodaj</button>
               @endif
            </td>
            <td><a href="{{ route('uczen.show', $sg->student->id) }}">{{ $sg->student->first_name }} {{ $sg->student->second_name }} {{ $sg->student->last_name }}</a></td>

            <!-- klasa ucznia -->
            <td class="c">
               @foreach( $sg->student->numbers as $studentNumber )
                  @if( !empty($grade) && $studentNumber->school_year_id == $schoolYearEnd && $studentNumber->grade_id == $grade->id)
                     {{$studentNumber->number}}
                  @endif
               @endforeach
            </td>

            <!-- okres przynależności do klasy -->
            @if($sg->confirmation_start==1) <td>{{ $sg->start }}</td>
            @else <td class="not_confirmation">{{ $sg->start }}</td>
            @endif
            @if($sg->confirmation_end==1) <td>{{ $sg->end }}</td>
            @else <td class="not_confirmation">{{ $sg->end }}</td>
            @endif

            <!-- modyfikowanie i usuwanie -->
            <td class="destroy edit c">
               <button class="edit btn btn-primary" data-student_grade_id="{{ $sg->id }}"><i class="fa fa-edit"></i></button>
               <button class="destroy btn btn-primary" data-student_grade_id="{{ $sg->id }}"><i class="fas fa-remove"></i></button>
            </td>
         </tr>
      @endforeach

      <tr class="create"><td colspan="7">
         <button id="showCreateRow" class="btn btn-primary"><i class="fa fa-plus"></i></button>
      </td></tr>
   </table>

   <div id="kopiowanie"></div>

   @if( !empty($grade) )
      <button id="editAll" class="btn btn-primary" data-href="{{ route('klasy_ucznia.editAll') }}">edytuj wszystkie</button>
      <p><a class="btn btn-primary" id="addMany" href="{{ route('klasy_ucznia.addMany') }}">dodaj wielu</a></p>
      <button id="createFromPreviousYear" class="btn btn-primary" style="display: none;">skopiuj uczniów, którzy ukończyli poprzedni rok szkolny</buton>
   @endif
</section>