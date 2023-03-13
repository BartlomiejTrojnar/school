@if( !empty( $links ) ) {!! $students->render() !!}   @endif

@if( $showDateView ) <p>Stan na <input type="date" id="dateView" value="{{ session()->get('dateView') }}" /></p>  @endif

<h2>{{ $subTitle }}</h2>
<table id="students">
<thead>
   <tr>
      <th>id</th>
      <?php
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
      <th>wpis</th>
      <th>aktualizacja</th>
      <th>zmień / usuń</th>
   </tr>

   @if( !empty( $links ) )
   <tr>
      <td>-</td>
      <td><?php  print_r($gradeSF);  ?></td>
      <td><?php  print_r($schoolYearSF);  ?></td>
      <?php /*<td>  print_r($groupSF);  </td> */ ?>
      <td colspan="10">=</td>
   </tr>
   @endif
</thead>

<tbody>
   @if( !empty($students) )
   <?php $count = 0; ?>
   @foreach($students as $student)
      <?php if($student->sex == 'mężczyzna') $class="man"; else $class="woman"; ?>
      <tr class="{{ $class }}" data-student_id="{{ $student->id }}">
         <td style="height: 35px;">{{ ++$count }}</td>
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
         <td class="small c">{{ substr($student->created_at, 0, 10) }}</td>
         <td class="small c">{{ substr($student->updated_at, 0, 10) }}</td>
         <td class="edit destroy c">
            <button class="edit btn btn-primary"      data-student_id="{{ $student->id }}"><i class="fa fa-edit"></i></button>
            <button class="destroy btn btn-primary"   data-student_id="{{ $student->id }}"><i class="fa fa-remove"></i></button>
         </td>
      </tr>
   @endforeach
   @endif

   @if( $showDateView )
      <tr class="create"><td colspan="11"><button id="showCreateRow" class="btn btn-primary"><i class="fa fa-plus"></i></button>
      <?php /*
      <tr class="create"><td colspan="12">
         <a id="studentCreate" class="btn btn-primary" href="{{ route('uczen.create') }}"><i class="fa fa-plus"></i></a>
      </td></tr>
      */ ?>

      <tr id="formStudentCreate" style="display: none;">
         <form action="{{ route('uczen.store') }}" method="post" role="form">
         {{ csrf_field() }}
            <td></td>
            <td><input type="text" name="first_name" size="10" maxlength="12" required autofocus placeholder="imie" /></td>
            <td><input type="text" name="second_name" size="10" maxlength="12" placeholder="drugie imie" /></td>
            <td><input type="text" name="last_name" size="10" maxlength="12" required placeholder="nazwisko" /></td>
            <td><input type="text" name="family_name" size="10" maxlength="12" placeholder="rodowe" /></td>
            <td><?php  print_r($sexSF);  ?></td>
            <td><input type="text" name="PESEL" size="11" maxlength="11" placeholder="PESEL" /></td>
            <td><input type="text" name="place_of_birth" size="18" maxlength="20" placeholder="miejsce urodzenia" /></td>

            <td colspan="4" class="c">
               <input type="hidden" name="history_view" value="{{ $_SERVER['HTTP_REFERER'] }}" />
               <button class="btn btn-primary" type="submit">dodaj</button>
               <a class="btn btn-primary" href="{{ $_SERVER['HTTP_REFERER'] }}">anuluj</a>
            </td>
         </form>
      </tr>
   @endif
</tbody>
</table>

<?php /*
@section('java-script')
   <script src="{{ asset('public/js/rememberDates.js') }}"></script>
   <script src="{{ asset('public/js/student/create.js') }}"></script>
@endsection
*/ ?>