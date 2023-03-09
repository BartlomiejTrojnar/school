<section id="studentGrades">
<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 09.03.2023 *********************** -->
   <aside style="float: right;">data widoku: {{$dateView}}<input id="yesterday" type="hidden" value="{{date('Y-m-d', strtotime('-1 day', strtotime($dateView)))}}" /></aside>
   <h2>klasy ucznia</h2>
   <table id="studentGradesTable">
      <tr>
         <th>lp</th>
         <th>nr księgi</th>
         <?php
            echo view('layouts.thSorting', ["thName"=>"klasa", "routeName"=>"klasy_ucznia.orderBy", "field"=>"grade_id", "sessionVariable"=>"StudentGradeOrderBy"]);
            echo view('layouts.thSorting', ["thName"=>"od", "routeName"=>"klasy_ucznia.orderBy", "field"=>"start", "sessionVariable"=>"StudentGradeOrderBy"]);
            echo view('layouts.thSorting', ["thName"=>"do", "routeName"=>"klasy_ucznia.orderBy", "field"=>"end", "sessionVariable"=>"StudentGradeOrderBy"]);
         ?>
         <th>popraw / usuń</th>
      </tr>

      <?php $count = 0; ?>
      @foreach($studentGrades as $sg)
         <!-- otwarcie wiersza dla ucznia -->
            <tr class="c" data-start="{{ $sg->start }}" data-end="{{ $sg->end }}" data-student_grade_id="{{ $sg->id }}">

            <td>{{ ++$count }}</td>
            <!-- numer z księgi ucznia -->
            @foreach($sg->student->bookOfStudents as $book)
               @if( $sg->grade->school_id == $book->school_id )
                  <td class="bookOfStudent" style="color: #f77; background-color: #228;" data-book_of_student_id="{{ $book->id }}">
                     {{ $book->number }}
                  </td>
               @endif
            @endforeach
            @if(count($sg->student->bookOfStudents)==0)
               <td class="showCreateFormForBookOfStudent">
                  <button class="btn btn-secondary"><i class="fa fa-plus"></i></button>
                  <aside class="createForm"></aside>
               </td>
            @endif

            <!-- klasa ucznia -->
            <td><a href="{{ route('klasa.show', $sg->grade_id) }}">
               @if($yearOfStudy && $yearOfStudy<=$sg->grade->year_of_graduation && $yearOfStudy>$sg->grade->year_of_beginning)
                  {{ $yearOfStudy-$sg->grade->year_of_beginning }}{{ $sg->grade->symbol }}
               @else
                  {{ $sg->grade->year_of_beginning }}-{{ $sg->grade->year_of_graduation }} {{ $sg->grade->symbol }}
               @endif
            </a></td>

            <!-- okres przynależności do klasy -->
            @if($sg->confirmation_start==1) <td>{{ $sg->start }}</td>
            @else <td class="not_confirmation">{{ $sg->start }}</td>
            @endif
            @if($sg->confirmation_end==1) <td>{{ $sg->end }}</td>
            @else <td class="not_confirmation">{{ $sg->end }}</td>
            @endif

            <!-- modyfikowanie i usuwanie -->
            <td class="destroy edit c" style="width: 150px;">
               <button class="edit btn btn-primary"            data-student_grade_id="{{ $sg->id }}"><i class="fa fa-edit"></i></button>
               <button class="exchange btn btn-primary"        data-student_grade_id="{{ $sg->id }}"><i class="fa fa-edit"></i></button>
               <button class="destroy btn btn-primary"         data-student_grade_id="{{ $sg->id }}"><i class="fa fa-remove"></i></button>
               <button class="removeYesterday btn btn-primary" data-student_grade_id="{{ $sg->id }}" title="usuń z klasy od wczoraj [{{date('Y-m-d', strtotime('-1 day', strtotime($dateView)))}}] (wraz z usunięciem z grup)"><i class="fa fa-user-times"></i></button>
            </td>
         </tr>
      @endforeach

      <tr class="create"><td colspan="6">
            <button class="showCreateRow btn btn-primary"><i class="fa fa-plus"></i></button>
      </td></tr>
   </table>
   <input id="lp" value="{{$count}}" hidden />
</section>

<?php echo $studentHistoryView; ?>
<?php echo $studentNumbersView; ?>