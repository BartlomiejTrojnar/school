<section id="examsTable">
<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 28.09.2022 *********************** -->
   <p class="c" style="color: #ff9;"><strong><em>Znaleziono {{$countExams}} egzaminów.</em></strong></p>
   <table id="exams">
      <thead>
         <tr>
            <th>id</th>
            <?php
               if($version!="forDeclaration")      echo view('layouts.thSorting', ["thName"=>"deklaracja", "routeName"=>"egzamin.orderBy", "field"=>"declaration_id", "sessionVariable"=>"ExamOrderBy"]);
               if($version!="forExamDescription")  echo view('layouts.thSorting', ["thName"=>"opis egzaminu", "routeName"=>"egzamin.orderBy", "field"=>"exam_description_id", "sessionVariable"=>"ExamOrderBy"]);
               echo view('layouts.thSorting', ["thName"=>"termin", "routeName"=>"egzamin.orderBy", "field"=>"term_id", "sessionVariable"=>"ExamOrderBy"]);
               echo view('layouts.thSorting', ["thName"=>"typ egzaminu", "routeName"=>"egzamin.orderBy", "field"=>"type", "sessionVariable"=>"ExamOrderBy"]);
               echo view('layouts.thSorting', ["thName"=>"punkty", "routeName"=>"egzamin.orderBy", "field"=>"points", "sessionVariable"=>"ExamOrderBy"]);
               echo view('layouts.thSorting', ["thName"=>"uwagi", "routeName"=>"egzamin.orderBy", "field"=>"comments", "sessionVariable"=>"ExamOrderBy"]);
            ?>
            <th>wprowadzono</th>
            <th>aktualizacja</th>
            <th>zmień / usuń</th>
         </tr>  
         @if($version!="forDeclaration")
            <tr>
               <td></td>
               <td><?php  print_r($declarationSF);  ?></td>
               <td><?php  print_r($termSF);  ?></td>
               <td><?php  print_r($examTypeSF);  ?></td>
               <td colspan="5"></td>
            </tr>
         @endif
      </thead>

      <tbody>
         <?php $count=0; ?>
         @foreach($exams as $exam)
            <tr data-exam_id="{{$exam->id}}">
               <td><a href="{{ route('egzamin.show', $exam->id) }}">{{ ++$count }}</a></td>

               @if($version!="forDeclaration")
                  <td>{{ $exam->declaration->student->first_name }} {{ $exam->declaration->student->last_name }} {{ $exam->declaration->application_number }}</td>
               @endif

               @if($version!="forExamDescription")
                  <td>
                     {{ $exam->examDescription->session->year }} {{ $exam->examDescription->session->type }} 
                     <spam style="color: yellow;">{{ $exam->examDescription->subject->name }}</spam> {{ $exam->examDescription->type }} {{ $exam->examDescription->level }}
                  </td>
               @endif

               <td>
                  @if($exam->term)
                     {{ substr($exam->term->date_start, 0, 16) }}-{{ substr($exam->term->date_end, 11, 5) }} sala {{ $exam->term->classroom->name }}
                  @endif
               </td>
               <td>{{ $exam->type }}</td>
               @if($exam->examDescription->max_points)
                  <td class="c">{{ $exam->points }} ({{number_format($exam->points/$exam->examDescription->max_points*100, 0)}}%)</td>
               @else
                  <td class="c">{{ $exam->points }}</td>
               @endif
               <td>{{ $exam->comments }}</td>
               <td class="small c">{{ substr($exam->created_at, 0, 10) }}</td>
               <td class="small c">{{ substr($exam->updated_at, 0, 10) }}</td>

               <!-- modyfikowanie i usuwanie -->
               <td class="destroy edit c">
                  <button class="edit btn btn-primary"    data-exam_id="{{ $exam->id }}" data-version="{{$version}}"><i class="fa fa-edit"></i></button>
                  <button class="destroy btn btn-primary" data-exam_id="{{ $exam->id }}"><i class="fas fa-remove"></i></button>
               </td>
            </tr>
         @endforeach

         <tr class="create"><td colspan="9">
            <button id="showCreateRow" class="btn btn-primary" data-version="{{$version}}"><i class="fa fa-plus"></i></button>
            <button id="showExamsCreateForDeclaration" class="btn btn-primary"><i class="fa fa-plus"></i> <i class="fa fa-plus"></i> dodaj kilka</button>
            <input type="hidden" name="lp" value="{{$count+1}}" />
         </td></tr>
      </tbody>
   </table>

   <?php /* obliczenie średniej punktów */
      $sum = 0; $avg = "N/N";
      if($count)  {
         foreach($exams as $exam)   $sum += $exam->points;
         $max_points = $exam->examDescription->max_points;
         if($max_points)  $avg = number_format((($sum/count($exams)) / $max_points)*100, 1);
      }
   ?>
   <p>Liczba egzaminów: {{ count($exams) }}, średnia: {{ $avg }}%</p>
</section>