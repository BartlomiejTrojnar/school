<!-- **********************  (C) mgr inż. Bartłomiej Trojnar; 12.07.2022 ********************** -->
@if( !empty( $links ) )
   {!! $subjects->render() !!}
@endif

<h2>{{ $subTitle }}</h2>
<table id="subjects">
   <thead>
      <tr>
         <th>lp</th>
         <?php
            echo view('layouts.thSorting', ["thName"=>"nazwa", "routeName"=>"przedmiot.orderBy", "field"=>"name", "sessionVariable"=>"SubjectOrderBy"]);
            echo view('layouts.thSorting', ["thName"=>"skrót", "routeName"=>"przedmiot.orderBy", "field"=>"short_name", "sessionVariable"=>"SubjectOrderBy"]);
            echo view('layouts.thSorting', ["thName"=>"aktualny?", "routeName"=>"przedmiot.orderBy", "field"=>"actual", "sessionVariable"=>"SubjectOrderBy"]);
            echo view('layouts.thSorting', ["thName"=>"kolejność w arkuszu", "routeName"=>"przedmiot.orderBy", "field"=>"order_in_the_sheet", "sessionVariable"=>"SubjectOrderBy"]);
            echo view('layouts.thSorting', ["thName"=>"rozszerzany?", "routeName"=>"przedmiot.orderBy", "field"=>"expanded", "sessionVariable"=>"SubjectOrderBy"]);
         ?>
         <th colspan="2">zmień / usuń</th>
      </tr>
   </thead>

   <tbody>
      <?php $count = 0; ?>
      @foreach($subjects as $subject)
         <?php $count++; ?>
         <tr data-subject_id={{ $subject->id }}>
            <td>{{ $count }}</td>
            <td><a href="{{ route('przedmiot.show', $subject->id) }}">{{ $subject->name }}</a></td>
            <td>{{ $subject->short_name }}</td>
            <td>@if( $subject->actual ) <i class="fa fa-check"></i> @endif</td>
            <td>{{ $subject->order_in_the_sheet }}</td>
            <td>@if( $subject->expanded ) <i class="fa fa-check"></i> @endif</td>
            <td class="edit destroy c">
               <button class="edit btn btn-primary"    data-subject_id="{{ $subject->id }}"><i class="fa fa-edit"></i></button>
               <button class="destroy btn btn-primary" data-subject_id="{{ $subject->id }}"><i class="fas fa-remove"></i></button>
            </td>
         </tr>
      @endforeach

      <tr class="create"><td colspan="7">
         <button id="showCreateRow" class="btn btn-primary"><i class="fa fa-plus"></i></button>
         <var id="countSubjects" hidden>{{$count}}</var>
      </td></tr>
   </tbody>
</table>