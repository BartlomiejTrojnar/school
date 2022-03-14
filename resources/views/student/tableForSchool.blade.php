<!-- **********************  (C) mgr inż. Bartłomiej Trojnar; 27.07.2021 ********************** -->
<h2>uczniowie szkoły</h2>
<div class="c">{!! $students->render() !!}</div>

@if( $showDateView )
    <p>Stan na <input type="date" id="dateView" value="{{ session()->get('dateView') }}" /></p>
@endif

<table id="students">
   <thead>
      <tr>
         <th>id</th>
         <?php
            echo view('layouts.thSorting', ["thName"=>"numer księgi", "routeName"=>"uczen.orderBy", "field"=>"number", "sessionVariable"=>"StudentOrderBy"]);
            echo view('layouts.thSorting', ["thName"=>"imię", "routeName"=>"uczen.orderBy", "field"=>"first_name", "sessionVariable"=>"StudentOrderBy"]);
            echo view('layouts.thSorting', ["thName"=>"drugie imię", "routeName"=>"uczen.orderBy", "field"=>"second_name", "sessionVariable"=>"StudentOrderBy"]);
            echo view('layouts.thSorting', ["thName"=>"nazwisko", "routeName"=>"uczen.orderBy", "field"=>"last_name", "sessionVariable"=>"StudentOrderBy"]);
         ?>
         <th>rodowe</th>
         <th>płeć</th>
         <?php
            echo view('layouts.thSorting', ["thName"=>"PESEL", "routeName"=>"uczen.orderBy", "field"=>"pesel", "sessionVariable"=>"StudentOrderBy"]);
            echo view('layouts.thSorting', ["thName"=>"miejsce urodzenia", "routeName"=>"uczen.orderBy", "field"=>"place_of_birth", "sessionVariable"=>"StudentOrderBy"]);
         ?>
      </tr>

      <tr class="c">
         <td>-</td>
         <td colspan="3"><?php  print_r($schoolYearSelectField);  ?></td>
         <td colspan="3"><?php  print_r($gradeSelectField);  ?></td>
         <td colspan="2">-</td>
      </tr>
   </thead>

   <tbody>
      @if( !empty($students) )
         <?php $count = 0; if(!empty($_GET['page'])) $count=($_GET['page']-1)*50; ?>
         @foreach($students as $student)
            <?php $count++; ?>
            @if($student->sex == 'mężczyzna')
               <tr class="man">
            @else
               <tr class="woman">
            @endif
               <td>{{ $count }}</td>
               <td>
                  @foreach($student->bookOfStudents as $bookNumber)
                     @if($bookNumber->school_id == $school->id)
                        {{ $bookNumber->number }}
                     @endif
                  @endforeach
               </td>
               <td>{{ $student->first_name }}</td>
               <td>{{ $student->second_name }}</td>
               <td><a href="{{ route('uczen.show', $student->id) }}">{{ $student->last_name }}</a></td>
               <td>{{ $student->family_name }}</td>
               <td>{{ $student->sex }}</td>
               @if( strlen($student->PESEL)==11 )
                  <td>{{ $student->PESEL }}</td>
               @else
                  <td style="color: red;">{{ $student->PESEL }} ({{ strlen($student->PESEL) }})</td>
               @endif
               <td>{{ $student->place_of_birth }}</td>
            </tr>
         @endforeach
      @endif
   </tbody>
</table>