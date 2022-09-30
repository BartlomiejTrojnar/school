<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 30.09.2022 *********************** -->
<section id="declarations">
   <h2>Deklaracje ucznia</h2>

   <?php $count=0; ?>
   @foreach($declarations as $declaration)
      <div class="declaration" data-declaration_id="{{ $declaration->id }}">
         <header>
            <i class="fa fa-minus" style="float: left; margin: 18px;"></i>
            <a href="{{ route('deklaracja.show', $declaration->id) }}">{{ $declaration->application_number }} {{ $declaration->session->year }} {{ $declaration->session->type }}</a>,
            kod ucznia: {{ $declaration->student_code }}; 
            liczba egzaminów: {{ count($declaration->exams) }}
            <span>
               wprowadzono: {{ substr($declaration->created_at, 0, 10) }}<br />
               aktualizacja: {{ substr($declaration->updated_at, 0, 10) }}
            </span>
            <button class="edit btn btn-primary"    data-declaration_id="{{ $declaration->id }}"><i class="fa fa-edit"></i></button>
            <button class="destroy btn btn-primary" data-declaration_id="{{ $declaration->id }}"><i class="fas fa-remove"></i></button>
         </header>
         <section class="exams">
            <table>
               <tr>
                  <th>opis egzaminu</th>
                  <th>rodzaj</th>
                  <th>termin</th>
                  <th>uwagi</th>
                  <th>punkty</th>
                  <th class="small">aktualizacja</th>
                  <th>zmień / usuń</th>
               </tr>
               @foreach($declaration->exams as $exam)
                  <tr data-exam_id="{{ $exam->id }}">
                     <td>
                        {{ $exam->examDescription->session->year }} {{ $exam->examDescription->session->type }}
                        <span style="color: yellow;">{{ $exam->examDescription->subject->name }}</span> {{ $exam->examDescription->type }} {{ $exam->examDescription->level }}
                     </td>
                     <td>{{$exam->type}}</td>
                     <td>{{$exam->term}}</td>
                     <td>{{$exam->comments}}</td>
                     <td class="c">{{$exam->points}}</td>
                     <td class="c small">{{substr($exam->updated_at, 0, 10)}}</td>
                     <td class="destroy edit c">
                        <button class="btn btn-primary editExam"    data-exam_id="{{ $exam->id }}"><i class="fa fa-edit"></i></button>
                        <button class="btn btn-primary destroyExam" data-exam_id="{{ $exam->id }}"><i class="fas fa-remove"></i></button>
                     </td>
                  </tr>
               @endforeach
               <tr class="examCreate c">
                  <td colspan="7">
                     <button class="showExamCreateRow btn btn-primary" data-declaration_id="{{$declaration->id}}"><i class="fa fa-plus"></i> dodaj egazmin</button>
                  </td>
               </tr>
            </table>
         </section>
      </div>
   @endforeach     

   <footer>
      <button id="showCreateTable" class="btn btn-primary" data-version="forStudent"><i class="fa fa-plus"></i> dodaj deklarację</button>
   </footer>
</section>