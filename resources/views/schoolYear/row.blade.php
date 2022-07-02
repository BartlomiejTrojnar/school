<!-- **********************  (C) mgr inż. Bartłomiej Trojnar; 02.07.2022 ********************** -->
<tr data-school_year_id="{{$sy->id}}">
   <td>{{ $lp }}</td>
   <td>{{ $sy->date_start }}</td>
   <td><a href="{{ route('rok_szkolny.show', $sy->id) }}">{{ $sy->date_end }}</a></td>
   <td>{{ $sy->date_of_classification_of_the_last_grade }}</td>
   <td>{{ $sy->date_of_graduation_of_the_last_grade }}</td>
   <td>{{ $sy->date_of_classification }}</td>
   <td>{{ $sy->date_of_graduation }}</td>
   <td class="edit destroy c">
      <button class="edit btn btn-primary" data-school_year_id="{{ $sy->id }}"><i class="fa fa-edit"></i></button>
      <button class="destroy btn btn-primary" data-school_year_id="{{ $sy->id }}"><i class="fas fa-remove"></i></button>
   </td>
</tr>