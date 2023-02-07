<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 12.11.2022 *********************** -->
<section id="taskRatingsTable">
   <h2>Oceny zadania</h2>
   <table id="taskRatings">
      <tr><th>lp</th>
      <?php
         echo view('layouts.thSorting', ["thName"=>"uczeń", "routeName"=>"ocena_zadania.orderBy", "field"=>"student_id", "sessionVariable"=>"TaskRatingOrderBy"]);
         echo view('layouts.thSorting', ["thName"=>"termin", "routeName"=>"ocena_zadania.orderBy", "field"=>"deadline", "sessionVariable"=>"TaskRatingOrderBy"]);
         echo view('layouts.thSorting', ["thName"=>"data realizacji", "routeName"=>"ocena_zadania.orderBy", "field"=>"implementation_date", "sessionVariable"=>"TaskRatingOrderBy"]);
         echo view('layouts.thSorting', ["thName"=>"wersja", "routeName"=>"ocena_zadania.orderBy", "field"=>"version", "sessionVariable"=>"TaskRatingOrderBy"]);
         echo view('layouts.thSorting', ["thName"=>"waga", "routeName"=>"ocena_zadania.orderBy", "field"=>"importance", "sessionVariable"=>"TaskRatingOrderBy"]);
         echo view('layouts.thSorting', ["thName"=>"data oceny", "routeName"=>"ocena_zadania.orderBy", "field"=>"rating_date", "sessionVariable"=>"TaskRatingOrderBy"]);
         echo view('layouts.thSorting', ["thName"=>"punkty", "routeName"=>"ocena_zadania.orderBy", "field"=>"points", "sessionVariable"=>"TaskRatingOrderBy"]);
         echo view('layouts.thSorting', ["thName"=>"ocena", "routeName"=>"ocena_zadania.orderBy", "field"=>"rating", "sessionVariable"=>"TaskRatingOrderBy"]);
         echo view('layouts.thSorting', ["thName"=>"uwagi", "routeName"=>"ocena_zadania.orderBy", "field"=>"comments", "sessionVariable"=>"TaskRatingOrderBy"]);
         echo view('layouts.thSorting', ["thName"=>"dziennik?", "routeName"=>"ocena_zadania.orderBy", "field"=>"diary", "sessionVariable"=>"TaskRatingOrderBy"]);
      ?>
         <th>data dziennika</th>
         <th>zmień / usuń</th>
      </tr>

      <tr>
         <td colspan="6"><?php
            print_r($gradeSF);
            print_r($groupSF);
         ?></td>
         <td colspan="4"></td>
         <td><?php  print_r($diarySF);  ?></td>
         <td colspan="3"></td>
      </tr>

      @if($taskRatings)
      <?php $count=0; ?>
      @foreach($taskRatings as $taskRating)
         <tr data-task_rating_id="{{ $taskRating->id }}">
            <td>{{ ++$count }}</td>
            <td style="text-align: left;"><a href="{{ route('uczen.show', $taskRating->student_id) }}">{{ $taskRating->student->first_name }} {{ $taskRating->student->last_name }}</a></td>
            <td>{{ substr($taskRating->deadline, 0, 10) }}</td>
            <td>{{ substr($taskRating->implementation_date, 0, 10) }}</td>
            <td>{{ $taskRating->version }}</td>
            <td>{{ $taskRating->importance }}</td>
            <td>{{ substr($taskRating->rating_date, 0, 10) }}</td>
            <td>{{ $taskRating->points }} ({{ number_format($taskRating->points/$taskRating->task->points*100,1) }}%)</td>
            <td>{{ $taskRating->rating }}</td>
            <td>{{ $taskRating->comments }}</td>
            <td class="diary">
               @if($taskRating->diary)
                  <button class="btn-warning entry-diary" data-task_rating_id="{{$taskRating->id}}"><i class='fas fa-circle'></i></button>
               @else
                  <button class="btn-warning no-diary" data-task_rating_id="{{$taskRating->id}}"><i class='far fa-circle'></i></button>
               @endif
            </td>
            <td class="entry_date">{{ substr($taskRating->entry_date, 0, 10) }}</td>

            <!-- modyfikowanie i usuwanie -->
            <td class="improvement edit destroy">
               <button class="improvement btn btn-primary"  data-task_rating_id="{{ $taskRating->id }}" title="poprawa"><i class="fa fa-edit"></i></button>
               <button class="edit btn btn-primary"         data-task_rating_id="{{ $taskRating->id }}" title="edytuj"><i class="fa fa-edit"></i></button>
               <button class="destroy btn btn-primary"      data-task_rating_id="{{ $taskRating->id }}" title="usuń"><i class="fas fa-remove"></i></button>
            </td>
         </tr>
      @endforeach
      @endif

      <tr><td colspan="14">
         <a class="btn btn-primary" href="{{ route('ocena_zadania.create') }}" id="showCreateRow"><i class="fa fa-plus"></i></a>
         <a class="btn btn-primary" href="{{ route('ocena_zadania.createLot') }}"><i class='fas fa-tasks'></i></a>
         <a class="btn btn-primary" href="{{ route('ocena_zadania.editLotTaskRatings') }}">zmień oceny zadań</a>
         <a class="btn btn-primary" href="{{ route('ocena_zadania.editLotTerms') }}">zmień terminy</a>
         <a class="btn btn-primary" href="{{ route('ocena_zadania.editLotImplementationDates') }}">zmień daty realizacji</a>
         <a class="btn btn-primary" href="{{ route('ocena_zadania.editLotImportances') }}">zmień wagi</a>
         <a class="btn btn-primary" href="{{ route('ocena_zadania.editLotPoints') }}">zmień punkty</a>
         <a class="btn btn-primary" href="{{ route('ocena_zadania.editLotRatings') }}">zmień oceny</a>
         <a class="btn btn-primary" href="{{ route('ocena_zadania.editLotRatingDates') }}">zmień daty oceny</a>
      </td></tr>
   </table>
</section>

<a class="btn btn-primary" href="{{ route('taskRatingImport.import', $task) }}">Importuj oceny <span class="glyphicon glyphicon-import"></span></a>