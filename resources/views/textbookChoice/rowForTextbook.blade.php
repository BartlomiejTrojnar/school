<!-- **********************  (C) mgr inż. Bartłomiej Trojnar; 15.10.2021 ********************** -->
<tr data-textbookChoice_id="{{ $textbookChoice->id }}" data-lp="{{$lp}}">
   <td>{{$lp}}</td>
   <td><a href="{{ route('szkola.show', $textbookChoice->school_id) }}">{{$textbookChoice->school->name}}</a></td>
   <td><a href="{{ route('rok_szkolny.show', $textbookChoice->school_year_id) }}">
      {{ substr($textbookChoice->schoolYear->date_start, 0, 4) }}/{{ substr($textbookChoice->schoolYear->date_end, 0, 4) }}
   </a></td>
   <td>{{$textbookChoice->learning_year}}</td>
   <td>{{$textbookChoice->level}}</td>

   <!-- modyfikowanie i usuwanie -->
   <td class="edit destroy c" style="width: 150px;">
      <button class="extension btn btn-primary" data-textbookChoice_id="{{ $textbookChoice->id }}"><i class="fa fa-arrow-right" title="przedłuż na następny rok szkolny"></i></button>
      <button class="edit btn btn-primary" data-textbookchoice_id="{{ $textbookChoice->id }}" title="modyfikuj"><i class="fa fa-edit"></i></button>
      <button class="destroy btn btn-primary" data-textbookchoice_id="{{ $textbookChoice->id }}"><i class="fas fa-remove" title="usuń"></i></button>
   </td>
</tr>