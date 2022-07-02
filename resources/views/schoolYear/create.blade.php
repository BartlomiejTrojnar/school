<!-- **********************  (C) mgr inż. Bartłomiej Trojnar; 30.06.2022 ********************** -->
<tr id="createRow" class="c">
   <form action="{{ route('rok_szkolny.store') }}" method="post" role="form">
      {{ csrf_field() }}
      <td colspan="2"><input type="date" name="dateStart" required autofocus /></td>
      <td><input type="date" name="dateEnd" required /></td>
      <td><input type="date" name="date_of_classification_of_the_last_grade" /></td>
      <td><input type="date" name="date_of_graduation_of_the_last_grade" /></td>
      <td><input type="date" name="date_of_classification" /></td>
      <td><input type="date" name="date_of_graduation" /></td>

      <!-- komórka z przyciskami dodawania i anulowania -->
      <td class="c" colspan="1" style="width: 175px;">
         <button id="add" class="btn btn-primary">dodaj</button>
         <button id="cancelAdd" class="btn btn-primary">anuluj</button>
      </td>
   </form>
</tr>