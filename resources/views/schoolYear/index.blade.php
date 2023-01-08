<!-- **********************  (C) mgr inż. Bartłomiej Trojnar; 08.01.2023 ********************** -->
@extends('layouts.app')

@section('java-script')
   <script language="javascript" type="module" src="{{ url('public/js/'.$js) }}"></script>
@endsection

@section('header')
   <h1>Lata szkolne</h1>
@endsection

@section('main-content')
{!! $schoolYears->render() !!}
<table id="schoolYears">
<thead>
   <tr>
      <th>id</th>
      <th>data rozpoczęcia</th>
      <th>data zakończenia</th>
      <th>data klasyfikacji<br />ostatnich klas</th>
      <th>data zakończenia nauki<br />ostatnich klas</th>
      <th>data<br />klasyfikacji</th>
      <th>data<br />zakończenia nauki</th>
      <th>zmień / usuń</th>
   </tr>
</thead>

<tbody>
   <?php $count = 0; ?>
   @foreach($schoolYears as $sy)
      <tr data-school_year_id="{{ $sy->id }}" class="c">
         <td>{{ ++$count }}</td>
         <td>{{ $sy->date_start }}</td>
         <td><a href="{{ route('rok_szkolny.show', $sy->id) }}">{{ $sy->date_end }}</a></td>
         <td>{{ $sy->date_of_classification_of_the_last_grade }}</td>
         <td>{{ $sy->date_of_graduation_of_the_last_grade }}</td>
         <td>{{ $sy->date_of_classification }}</td>
         <td>{{ $sy->date_of_graduation }}</td>
         <td class="edit destroy c">
            <button class="edit btn btn-primary"      data-school_year_id="{{ $sy->id }}"><i class="fa fa-edit"></i></button>
            <button class="destroy btn btn-primary"   data-school_year_id="{{ $sy->id }}"><i class="fa fa-remove"></i></button>
         </td>
      </tr>
   @endforeach
   <tr class="create"><td colspan="8"><button id="showCreateRow" class="btn btn-primary"><i class="fa fa-plus"></i></button>
</tbody>
</table>
<input type="hidden" id="countSchoolYears" value="{{ count($schoolYears) }}" />
@endsection