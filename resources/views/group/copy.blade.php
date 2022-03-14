@extends('layouts.app')

@section('java-script')
   <script language="javascript" type="text/javascript" src="{{ asset('public/js/group.js') }}"></script>
@endsection

@section('header')
   <h1>Dodawanie grupy</h1>
@endsection

@section('main-content')
   <form action="{{ route('grupa.copyStore') }}" method="post" role="form">
   {{ csrf_field() }}
      <table>
         <tr>
            <th><label for="subject_id">przedmiot</label></th>
            <td><?php  print_r($subjectSelectField);  ?></td>
         </tr>
         <tr>
            <th><label for="date_start">data początkowa</label></th>
            <td><input id="dateStart" type="date" name="date_start" required value="{{$group->date_start}}" /></td>
            <td>
               <button class="btn btn-primary proposedGradeDateStart">{{ date('Y-m-d') }}</button>
               <button class="btn btn-primary proposedGradeDateStart">{{ $proposedDates['dateOfStartSchoolYear'] }}</button>
            </td>
         </tr>
         <tr>
            <th><label for="date_end">data końcowa</label></th>
            <td><input id="dateEnd" type="date" name="date_end" required value="{{$group->date_end}}" /></td>
            <td>
               <button class="btn btn-primary proposedGradeDateEnd">{{ $proposedDates['dateOfGraduationOfTheLastGrade'] }}</button>
               <button class="btn btn-primary proposedGradeDateEnd">{{ $proposedDates['dateOfGraduationSchoolYear'] }}</button>
            </td>
         </tr>
         <tr>
            <th><label for="comments">uwagi</label></th>
            <td><input type="text" name="comments" size="10" maxlength="30" value="{{$group->comments}}" /></td>
         </tr>
         <tr>
            <th><label for="level">poziom</label></th>
            <td><?php  print_r($levelSelectField);  ?></td>
         </tr>
         <tr>
            <th><label for="hours">godziny</label></th>
            <td><input type="number" name="hours" size="2" maxlength="1" value="{{$group->hours}}" /></td>
         </tr>

         <!-- klasy -->
         <tr>
            <th>klasy</th>
            <td>
               @foreach($group->grades as $gg)
                  <input type="checkbox" class="grade" name="grade{{$gg->grade_id}}" value="{{$gg->grade_id}}" checked />
                  {{$gg->grade->year_of_beginning}}-{{$gg->grade->year_of_graduation}} {{$gg->grade->symbol}}<br />
               @endforeach
            </td>
         </tr>

         <!-- nauczyciel -->
         <tr>
            <th>nauczyciel</th>
            <td>
               @foreach($group->teachers as $gt)
                  <input type="checkbox" class="teacher" name="teacher{{$gt->teacher_id}}" value="{{$gt->teacher_id}}" checked />
                  {{$gt->teacher->first_name}} {{$gt->teacher->last_name}} {{$gt->date_start}} {{$gt->date_end}}<br />
               @endforeach
            </td>
            <td class="alert alert-info">ograniczyć do jednego - aktualnego<br />z datą od początku do końca grupy<br />Po zmianie przedmiotu - wyświetlić propozycję nauczycieli</td>
         </tr>

         <!-- uczniowie -->
         <tr>
            <th>uczniowie</th>
            <td>
               @foreach($group->students as $gs)
                  <input type="checkbox" class="student" name="student{{$gs->student_id}}" value="{{$gs->student_id}}" checked />
                  {{$gs->student->first_name}} {{$gs->student->last_name}} {{$gs->date_start}} {{$gs->date_end}}<br />
               @endforeach
            </td>
            <td class="alert alert-info">ograniczyć do uczniów z wybranych klas<br />wpisać daty uczniów z oryginalnej grupy,<br /> chyba że zostały zmienione daty grupy</td>
         </tr>

         <tr class="submit"><td colspan="2">
            <input type="hidden" name="history_view" value="{{ $_SERVER['HTTP_REFERER'] }}" />
            <button class="btn btn-primary" type="submit">dodaj</button>
            <a class="btn btn-primary" href="{{ route('grupa.index') }}">anuluj</a>
         </td></tr>
      </table>
   </form>

   <div id="error" class="alert alert-info hide"></div>
@endsection