<!-- **********************  (C) mgr inż. Bartłomiej Trojnar; 31.12.2022 ********************** -->
<h2>Klasy w szkole</h2>
<div class="c">{!! $grades->render() !!}</div>
<table id="grades">
   <thead>
      <tr>
         <th>id</th>
         <?php
            echo view('layouts.thSorting', ["thName"=>"klasa", "routeName"=>"klasa.orderBy", "field"=>"year_of_beginning", "sessionVariable"=>"GradeOrderBy"]);
         ?>
         <th>zmień / usuń</th>
      </tr>

      <tr><td colspan="3" class="c"><?php  print_r($schoolYearSF);  ?></td></tr>
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
            <td class="edit destroy c">
               <button class="edit btn btn-primary"    data-grade_id="{{ $grade->id }}"><i class="fa fa-edit"></i></button>
               <button class="destroy btn btn-primary" data-grade_id="{{ $grade->id }}"><i class="fa fa-remove"></i></button>
            </td>
         </tr>
      @endforeach

      <tr class="create"><td colspan="3"><button id="showCreateRow" class="btn btn-primary"><i class="fa fa-plus"></i></button>
   </tbody>
</table>
<input id="countGrades" type="hidden" value="{{ count($grades) }}" />