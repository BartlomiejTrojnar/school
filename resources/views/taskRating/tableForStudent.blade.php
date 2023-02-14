<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 14.02.2023 *********************** -->
<section id="taskRatingsTable">
   <h2>Oceny zadań ucznia</h2>
   <table id="taskRatings">
      <tr>
         <th>lp</th>
         <?php
            echo view('layouts.thSorting', ["thName"=>"zadanie", "routeName"=>"ocena_zadania.orderBy", "field"=>"task_id", "sessionVariable"=>"TaskRatingkOrderBy"]);
            echo view('layouts.thSorting', ["thName"=>"termin", "routeName"=>"ocena_zadania.orderBy", "field"=>"deadline", "sessionVariable"=>"TaskRatingkOrderBy"]);
            echo view('layouts.thSorting', ["thName"=>"data realizacji", "routeName"=>"ocena_zadania.orderBy", "field"=>"implementation_date", "sessionVariable"=>"TaskRatingkOrderBy"]);
            echo view('layouts.thSorting', ["thName"=>"wersja", "routeName"=>"ocena_zadania.orderBy", "field"=>"version", "sessionVariable"=>"TaskRatingkOrderBy"]);
            echo view('layouts.thSorting', ["thName"=>"waga", "routeName"=>"ocena_zadania.orderBy", "field"=>"importance", "sessionVariable"=>"TaskRatingkOrderBy"]);
            echo view('layouts.thSorting', ["thName"=>"data oceny", "routeName"=>"ocena_zadania.orderBy", "field"=>"rating_date", "sessionVariable"=>"TaskRatingkOrderBy"]);
            echo view('layouts.thSorting', ["thName"=>"punkty", "routeName"=>"ocena_zadania.orderBy", "field"=>"points", "sessionVariable"=>"TaskRatingkOrderBy"]);
            echo view('layouts.thSorting', ["thName"=>"ocena", "routeName"=>"ocena_zadania.orderBy", "field"=>"rating", "sessionVariable"=>"TaskRatingkOrderBy"]);
            echo view('layouts.thSorting', ["thName"=>"uwagi", "routeName"=>"ocena_zadania.orderBy", "field"=>"comments", "sessionVariable"=>"TaskRatingkOrderBy"]);
            echo view('layouts.thSorting', ["thName"=>"dziennik?", "routeName"=>"ocena_zadania.orderBy", "field"=>"diary", "sessionVariable"=>"TaskRatingkOrderBy"]);
         ?>
         <th>data dziennika</th>
         <th>operacje</th>
      </tr>

      @if($taskRatings)
      <?php $count=0; $sumPoints=0; $sumImportances=0; ?>
      @foreach($taskRatings as $taskRating)
         <?php 
            if($taskRating->importance) $sumPoints += $taskRating->points/$taskRating->task->points + 0.3;
            $sumImportances += $taskRating->importance;
         ?>
         <tr data-task_rating_id="{{$taskRating->id}}">
            <td class="lp"><a href="{{ route('ocena_zadania.show', $taskRating->id) }}">{{ ++$count }}</a></td>
            <td style="text-align: left;"><a href="{{ route('zadanie.show', $taskRating->task_id) }}">{{ $taskRating->task->name }}</a></td>
            <td>{{ substr($taskRating->deadline, 0, 10) }}</td>
            <td>{{ substr($taskRating->implementation_date, 0, 10) }}</td>
            <td>{{ $taskRating->version }}</td>
            <td>{{ $taskRating->importance }}</td>
            <td>{{ substr($taskRating->rating_date, 0, 10) }}</td>
            <td>{{ $taskRating->points }} ({{ number_format($taskRating->points/$taskRating->task->points*100, 1) }}%)</td>
            <td>{{ $taskRating->rating }}</td>
            <td>{{ $taskRating->comments }}</td>
            <td class="diary">
               @if($taskRating->diary)
                  <button class="btn-warning entry-diary" data-task_rating_id="{{ $taskRating->id }}"><i class="fa fa-check-circle"></i></button>
               @else
                  <button class="btn-warning no-diary" data-task_rating_id="{{ $taskRating->id }}"><i class="fa fa-circle-o"></i></button>
               @endif
            </td>
            <td class="entry_date">{{ substr($taskRating->entry_date, 0, 10) }}</td>

            <!-- modyfikowanie i usuwanie -->
            <td class="improvement edit destroy">
               <button class="improvement btn btn-primary"  data-task_rating_id="{{ $taskRating->id }}" title="poprawa"><i class="fa fa-clone"></i></button>
               <button class="edit btn btn-primary"         data-task_rating_id="{{ $taskRating->id }}" title="edytuj"><i class="fa fa-edit"></i></button>
               <button class="destroy btn btn-primary"      data-task_rating_id="{{ $taskRating->id }}" title="usuń"><i class="fa fa-remove"></i></button>
            </td>
         </tr>
      @endforeach
      @endif

      <tr class="create"><td colspan="13">
         <input type="number" name="lp" id="lp" value="{{ $count }}" hidden>
         <button id="showCreateRow" class="btn btn-primary"><i class="fa fa-plus"></i></button>
      </td></tr>
      <tr class="c"><td colspan="13"><a class="btn btn-primary" href="{{ route('ocena_zadania.editStudentRatings') }}">zmień wszystkie</a></td></tr>
   </table>

   <input id="countTaskRatings" type="hidden" value="{{ count($taskRatings) }}" />

   @if($sumImportances)
      <p>procent punktów: {{ number_format($sumPoints*100, 0) }} / {{ $sumImportances }} = {{ number_format($sumPoints/$sumImportances*100, 1) }}%</p>
   @else
      <p>procent punktów: --- = ---%</p>
   @endif
</section>