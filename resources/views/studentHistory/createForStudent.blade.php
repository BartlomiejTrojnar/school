<!-- **********************  (C) mgr inż. Bartłomiej Trojnar; 28.07.2021 ********************** -->
<tr class="createRow">
   <form action="{{ route('historia_ucznia.store') }}" method="post" role="form">
      {{ csrf_field() }}
      <td>
         <!-- uczeń (ukryte pole z id ucznia) -->
         <input type="hidden" name="student_id" value="{{$student->id}}" />
         <!-- data wydarzenia -->
         <input type="date" name="date" value="{{ session()->get('dateView') }}" />
         <input type="checkbox" name="confirmation_date" checked="checked" />
      </td>
      <!-- opis wydarzenia -->
      <td>
         <input type="text" name="event" size="27" maxlength="27" value="przyjęto do szkoły" />
         <input type="checkbox" name="confirmation_event" checked="checked" />
      </td>
      <!-- komórka z przyciskami dodawania i anulowania -->
      <td class="c" style="width: 175px;">
         <button class="add btn btn-primary">dodaj</button>
         <button class="cancelAdd btn btn-primary">anuluj</button>
      </td>
   </form>
</tr>