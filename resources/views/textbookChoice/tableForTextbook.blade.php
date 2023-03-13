<!-- **********************  (C) mgr inż. Bartłomiej Trojnar; 05.07.2022 ********************** -->
<section id="textbookChoicesTable">
   <h2>Wybory podręcznika</h2>
   <table id="textbookChoices">
      <thead>
         <tr>
            <th>lp.</th>
            <?php
               echo view('layouts.thSorting', ["thName"=>"szkoła", "routeName"=>"wybor_podrecznika.orderBy", "field"=>"school_id", "sessionVariable"=>"TextbookChoiceOrderBy"]);
               echo view('layouts.thSorting', ["thName"=>"rok szkolny", "routeName"=>"wybor_podrecznika.orderBy", "field"=>"school_year_id", "sessionVariable"=>"TextbookChoiceOrderBy"]);
            ?>
            <th>wybrano dla klasy<br />(rok nauki)</th>
            <th>poziom</th>
            <th>zmień/usuń</th>
         </tr>

         <tr>
            <td></td>
            <td><?php  print_r($schoolSelectField);  ?></td>
            <td><?php  print_r($schoolYearSelectField);  ?></td>
            <td><?php  print_r($studyYearSelectField);  ?></td>
            <td><?php  print_r($levelSelectField);  ?></td>
            <td></td>
         </tr>
      </thead>

      <tbody>
         <?php $count=0; ?>
         @foreach($textbookChoices as $textbookChoice)
         <tr data-textbookChoice_id="{{$textbookChoice->id}}">
            <td>{{++$count}}</td>
            <td><a href="{{ route('szkola.show', $textbookChoice->school_id) }}">{{$textbookChoice->school->name}}</a></td>
            <td><a href="{{ route('rok_szkolny.show', $textbookChoice->school_year_id) }}">
               {{ substr($textbookChoice->schoolYear->date_start, 0, 4) }}/{{ substr($textbookChoice->schoolYear->date_end, 0, 4) }}
            </a></td>
            <td>{{$textbookChoice->learning_year}}</td>
            <td>{{$textbookChoice->level}}</td>

            <!-- modyfikowanie i usuwanie -->
            <td class="destroy edit c">
               <button class="edit btn btn-primary" data-textbookChoice_id="{{ $textbookChoice->id }}"><i class="fa fa-edit"></i></button>
               <button class="destroy btn btn-primary" data-textbookChoice_id="{{ $textbookChoice->id }}"><i class="fas fa-remove"></i></button>
            </td>
         </tr>
         @endforeach
         <tr class="create"><td colspan="7">
               <button id="showCreateRow" class="btn btn-primary"><i class="fa fa-plus"></i></button>
               <var id="countChoices" hidden>{{ $count }}</var>
         </td></tr>
      </tbody>
   </table>
</section>