<tr class="editRow" data-school_year_id="{{ $schoolYear->id }}">
<!-- **********************  (C) mgr inż. Bartłomiej Trojnar; 10.01.2023 ********************** -->
   <form action="{{ route('rok_szkolny.update', $schoolYear->id) }}" method="post" role="form">
      {{ csrf_field() }}
      {{ method_field('PATCH') }}
      <td><var class="lp">{{ $lp }}</var></td>
      <td>
         <input type="hidden" name="id" value="{{ $schoolYear->id }}" />
         <input type="date" name="dateStart" value="{{ $schoolYear->date_start }}" required />
      </td>
      <td><input type="date" name="dateEnd" value="{{ $schoolYear->date_end }}" required /></td>
      <td><input type="date" name="date_of_classification_of_the_last_grade" value="{{ $schoolYear->date_of_classification_of_the_last_grade }}" /></td>
      <td><input type="date" name="date_of_graduation_of_the_last_grade" value="{{ $schoolYear->date_of_graduation_of_the_last_grade }}" /></td>
      <td><input type="date" name="date_of_classification" value="{{ $schoolYear->date_of_classification }}" /></td>
      <td><input type="date" name="date_of_graduation" value="{{ $schoolYear->date_of_graduation }}" /></td>

      <!-- komórka z przyciskami potwierdzenia zmiany i anulowania -->
      <td class="c" style="width: 225px;">
         <button class="update btn btn-primary"       data-school_year_id="{{ $schoolYear->id }}">zapisz zmiany</button>
         <button class="cancelUpdate btn btn-primary" data-school_year_id="{{ $schoolYear->id }}">anuluj</button>
      </td>
   </form>
</tr>