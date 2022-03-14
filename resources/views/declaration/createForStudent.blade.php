<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 11.11.2021 *********************** -->
<table id="createTable">
   <tr>
      <th>sesja</th>
      <th>numer zgłoszenia</th>
      <th>kod ucznia</th>
   </tr>
   <form action="{{ route('deklaracja.store') }}" method="post" role="form">
      {{ csrf_field() }}
      <tr>
         <td><?php  print_r($session);  ?></td>
         <td class="c"><input type="number" name="application_number" min="1" max="10" size="3" required /></td>
         <td class="c"><input type="text" name="student_code" size="2" maxlength="3" /></td>
      </tr>
      <tr>
         <!-- komórka z przyciskami dodawania i anulowania -->
         <td class="c" colspan="4">
            <input type="hidden" name="student_id" value="{{$student}}" />
            <button id="add" class="btn btn-primary">dodaj</button>
            <button id="cancelAdd" class="btn btn-primary">anuluj</button>
         </td>
      </tr>
   </form>
</table>