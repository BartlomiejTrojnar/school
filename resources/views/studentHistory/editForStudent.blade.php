<!-- **********************  (C) mgr inż. Bartłomiej Trojnar; 28.07.2021 ********************** -->
<tr class="editRow" data-student_history_id="{{$studentHistory->id}}">
   <form action="{{ route('historia_ucznia.update', $studentHistory->id) }}" method="post" role="form">
      {{ csrf_field() }}
      {{ method_field('PATCH') }}
      <td>
         <!-- id i uczeń (pola ukryte) -->
         <input type="hidden" name="id" value="{{$studentHistory->id}}" />
         <input type="hidden" name="student_id" value="{{$studentHistory->student_id}}" />
         <!-- data wydarzenia -->
         <input type="date" name="date" value="{{ $studentHistory->date }}" />
         <input type="checkbox" name="confirmation_date" @if($studentHistory->confirmation_date)checked="checked"@endif />
      </td>

      <!-- opis wydarzenia -->
      <td>
         <input type="text" name="event" value="{{ $studentHistory->event }}" size="27" maxlength="27" />
         <input type="checkbox" name="confirmation_event" @if($studentHistory->confirmation_event)checked="checked"@endif />
      </td>

      <!-- komórka z przyciskami potwierdzenia zmiany i anulowania -->
      <td class="c" style="width: 240px;">
         <button data-student_history_id="{{$studentHistory->id}}" class="update btn btn-primary">zapisz zmiany</button>
         <button data-student_history_id="{{$studentHistory->id}}" class="cancelUpdate btn btn-primary">anuluj</button>
      </td>
   </form>
</tr>