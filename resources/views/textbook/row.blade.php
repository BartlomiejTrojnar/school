<!-- **********************  (C) mgr inż. Bartłomiej Trojnar; 13.10.2021 ********************** -->
<tr data-textbook_id="{{$textbook->id}}">
   <td>{{ $lp }}</td>
   <td><a href="{{ route('przedmiot.show', $textbook->subject_id) }}">{{ $textbook->subject->name }}</a></td>
   <td>{{ $textbook->author }}</td>
   <td><a href="{{ route('podrecznik.show', $textbook->id) }}">{{ $textbook->title }}</a></td>
   <td>{{ $textbook->publishing_house }}</td>
   <td>{{ $textbook->admission }}</td>
   <td>{{ $textbook->comments }}</td>
   <td class="c">{{ count($textbook->textbookChoices) }}</td>
   <td>{{ substr($textbook->updated_at, 0, 10) }}</td>
   <td class="edit destroy c">
      <button class="edit btn btn-primary" data-textbook_id="{{ $textbook->id }}"><i class="fa fa-edit"></i></button>
      <button class="destroy btn btn-primary" data-textbook_id="{{ $textbook->id }}"><i class="fas fa-remove"></i></button>
   </td>
</tr>