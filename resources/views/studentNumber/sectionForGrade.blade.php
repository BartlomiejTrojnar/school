<section id="studentNumbers">
<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 16.06.2023 *********************** -->
   <h3>numery uczniów w dziennikach</h3>
   <div id="tips">
      @if( !empty($grade) && !empty($yearOfStudy) && $yearOfStudy>1 )
         <section id="copyStudentNumbers">
            <button class="run btn btn-primary"><i class="fa fa-files-o"></i> kopiuj numery</button>
            <button class="help"><i class='fa fa-question-circle' style='font-size:24px'></i></button>
            <div class="answer">Kliknięcie przycisku powoduje skopiowanie numerów z poprzedniego roku szkolnego na wybrany rok szkolny.
               Funkcja ta działa jeżeli w wybranym roku szkolnym nie ma jeszcze żadnego wpisanego numeru.</div>
         </section>
      @endif

      <section id="moveNumbers">
         <button id="button_up" class="btn btn-primary" disabled><i class="fa fa-arrow-circle-up" style="font-size:16px;"></i> w górę</button>
         <button id="button_down" class="btn btn-primary" disabled><i class="fa fa-arrow-circle-down" style="font-size:16px;"></i> w dół</button>
         <input type="hidden" id="student_number_id" />
         <button class="help"><i class='fa fa-question-circle' style='font-size:24px'></i></button>
         <div class="answer">Aby zmienić kolejność uczniów w klasie, wskaż (kliknij) wiersz ucznia a następnie przyciskami przenieś wyżej lub niżej. Numery zapiszą się automatycznie.</div>
      </section>

      <section id="confirmNumbers">
         <button class="run btn btn-primary"><i class="fa fa-lock"></i> potwierdź numery</button>
         <button class="help"><i class='fa fa-question-circle' style='font-size:24px'></i></button>
         <div class="answer">Kliknięcie przycisku powoduje potwierdzenie wszystkich widocznych numerów.</div>
      </section>

      <section id="addNumbersForGrade">
         <button class="run btn btn-primary"><i class="fa fa-plus-square"></i> dodaj numery dla wszystkich uczniów klasy</button>
         <button class="help"><i class='fa fa-question-circle' style='font-size:24px'></i></button>
         <div class="answer">Funkcja znajduje wszystkich uczniów bieżącej klasy w kolejności alfabetycznej i nadaje im numery na bieżący rok szkolny.</div>
      </section>

      <section id="importNumbers">
         <button class="run btn btn-primary"><i class="fa fa-map-signs" aria-hidden="true"></i> <a href="{{ url('numery_ucznia/importMenu') }}">importuj</a></button>
         <button class="help"><i class='fa fa-question-circle' style='font-size:24px'></i></button>
         <div class="answer">Po naciśnięciu przejdziesz do strony importowania numerów uczniów.</div>
      </section>
   </div>

   <?php echo $tableForGrade; ?>

   <div>
      <input id="lastLP" type="hidden" value="{{ $count }}" />
   </div>
</section>