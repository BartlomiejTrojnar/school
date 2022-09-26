<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 26.09.2022 *********************** -->
@if( !empty( $links ) )
   {!! $examDescriptions->render() !!}
@endif

<h2>opisy egzaminów</h2>
<p class="c" style="color: #ff9;"><strong><em>Znaleziono {{$countDesc}} opisów egzaminów.</em></strong></p>
<table id="examDescriptions">
   <thead>
      <tr>
         <th>id</th>
         <?php
            echo view('layouts.thSorting', ["thName"=>"przedmiot",      "routeName"=>"examDescription.orderBy", "field"=>"subject_id", "sessionVariable"=>"ExamDescriptionOrderBy"]);
            echo view('layouts.thSorting', ["thName"=>"typ egzaminu",   "routeName"=>"examDescription.orderBy", "field"=>"type", "sessionVariable"=>"ExamDescriptionOrderBy"]);
            echo view('layouts.thSorting', ["thName"=>"poziom",         "routeName"=>"examDescription.orderBy", "field"=>"level", "sessionVariable"=>"ExamDescriptionOrderBy"]);
            echo view('layouts.thSorting', ["thName"=>"max punktów",    "routeName"=>"examDescription.orderBy", "field"=>"max_points", "sessionVariable"=>"ExamDescriptionOrderBy"]);
         ?>
         <th>liczba zdających</th>
         <th>średnia</th>
         <th>wprowadzono</th>
         <th>aktualizacja</th>
         <th>zmień / usuń</th>
      </tr>
      <tr>
         <td></td>
         <td><?php  print_r($subjectSelectField);  ?></td>
         <td><?php  print_r($examTypeSelectField);  ?></td>
         <td><?php  print_r($levelSelectField);  ?></td>
         <td colspan="6"></td>
      </tr>
   </thead>

   <tbody>
      <?php 
         if(isset($_GET['page']))   $count = ($_GET['page']-1)*25;
         else  $count = 0;
      ?>
      @foreach($examDescriptions as $examDescription)
         <tr data-exam_description_id="{{$examDescription->id}}">
            <td><a href="{{ route('opis_egzaminu.show', $examDescription->id) }}">{{ ++$count }}</a></td>
            <td><a href="{{ route('przedmiot.show', $examDescription->subject_id) }}">
               {{ $examDescription->subject->name }}
            </a></td>
            <td>{{ $examDescription->type }}</td>
            <td>{{ $examDescription->level }}</td>
            <td class="c">{{ $examDescription->max_points }}</td>
            <td class="c">{{ count($examDescription->exams) }}</td>
            <?php /* obliczenie średniej punktów z egzaminów */ 
               $sum = 0;
               foreach($examDescription->exams as $exam)
                  $sum += $exam->points;
               if($examDescription->max_points && count($examDescription->exams))
                  $avg = number_format((($sum/count($examDescription->exams)) / $examDescription->max_points)*100, 1);
               else $avg="N/N";
            ?>
            <td class="c">{{ $avg }}%</td>
            <td class="small c">{{ substr($examDescription->created_at, 0, 10) }}</td>
            <td class="small c">{{ substr($examDescription->updated_at, 0, 10) }}</td>

            <!-- modyfikowanie i usuwanie -->
            <td class="destroy edit c">
               <button class="edit btn btn-primary" data-exam_description_id="{{ $examDescription->id }}"><i class="fa fa-edit"></i></button>
               <button class="destroy btn btn-primary" data-exam_description_id="{{ $examDescription->id }}"><i class="fas fa-remove"></i></button>
            </td>
         </tr>
      @endforeach

      <tr class="create"><td colspan="10">
         <button id="showCreateRow" class="btn btn-primary"><i class="fa fa-plus"></i></button>
         <input type="hidden" name="lp" value="{{$count+1}}" />
      </td></tr>
   </tbody>
</table>