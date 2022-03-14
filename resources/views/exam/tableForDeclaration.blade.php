<section id="examsTable">
<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 24.11.2021 *********************** -->
   <p class="c" style="color: #ff9;"><strong><em>Znaleziono {{$countExams}} egzaminów.</em></strong></p>
   <table id="exams">
      <thead>
         <tr>
            <th>id</th>
            <?php
               echo view('layouts.thSorting', ["thName"=>"opis egzaminu", "routeName"=>"egzamin.orderBy", "field"=>"exam_description_id", "sessionVariable"=>"ExamOrderBy"]);
               echo view('layouts.thSorting', ["thName"=>"termin", "routeName"=>"egzamin.orderBy", "field"=>"term_id", "sessionVariable"=>"ExamOrderBy"]);
               echo view('layouts.thSorting', ["thName"=>"typ egzaminu", "routeName"=>"egzamin.orderBy", "field"=>"type", "sessionVariable"=>"ExamOrderBy"]);
               echo view('layouts.thSorting', ["thName"=>"punkty", "routeName"=>"egzamin.orderBy", "field"=>"points", "sessionVariable"=>"ExamOrderBy"]);
               echo view('layouts.thSorting', ["thName"=>"uwagi", "routeName"=>"egzamin.orderBy", "field"=>"comments", "sessionVariable"=>"ExamOrderBy"]);
            ?>
            <th>wprowadzono</th>
            <th>aktualizacja</th>
            <th>zmień / usuń</th>
         </tr>
      </thead>

      <tbody>
         <?php $count=0; ?>
         @foreach($exams as $exam)
            <tr data-exam_id="{{$exam->id}}" @if($exam->type=='dodatkowy')class="dodatkowy"@endif>
               <td><a href="{{ route('egzamin.show', $exam->id) }}">{{ ++$count }}</a></td>
               <td>
                  {{ $exam->examDescription->session->year }} {{ $exam->examDescription->session->type }} 
                  <spam style="color: yellow;">{{ $exam->examDescription->subject->name }}</spam> {{ $exam->examDescription->type }} {{ $exam->examDescription->level }}
               </td>
               <td>
                  @if($exam->term)
                     {{ substr($exam->term->date_start, 0, 16) }}-{{ substr($exam->term->date_end, 11, 5) }} sala {{ $exam->term->classroom->name }}
                  @endif
               </td>
               <td>{{ $exam->type }}</td>
               <td>{{ $exam->points }}</td>
               <td>{{ $exam->comments }}</td>
               <td>{{ $exam->created_at }}</td>
               <td>{{ $exam->updated_at }}</td>

               <!-- modyfikowanie i usuwanie -->
               <td class="destroy edit c">
                  <button class="edit btn btn-primary" data-exam_id="{{ $exam->id }}"><i class="fa fa-edit"></i></button>
                  <button class="destroy btn btn-primary" data-exam_id="{{ $exam->id }}"><i class="fas fa-remove"></i></button>
               </td>
            </tr>
         @endforeach

         <tr class="create"><td colspan="10">
            <button id="showCreateRow" class="btn btn-primary"><i class="fa fa-plus"></i></button>
            <button id="showExamsCreateForDeclaration" class="btn btn-primary"><i class="bi bi-journal-plus"></i></button>
            <input type="hidden" name="lp" value="{{$count+1}}" />
         </td></tr>
      </tbody>
   </table>
</section>