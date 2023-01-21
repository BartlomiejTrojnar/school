<tr id="createRow">
<!-- **********************  (C) mgr inż. Bartłomiej Trojnar; 21.01.2023 ********************** -->
   <form action="{{ route('rozszerzenie.store') }}" method="post" role="form">
      {{ csrf_field() }}
      <td colspan="2"><?php  print_r($studentSF);  ?></td>
      <td><?php  print_r($subjectSF);  ?></td>
      <td><input type="text" name="level" size="12" maxlength="12" require /></td>
      <td><input type="date" name="choice" /></td>
      <td><input type="date" name="resignation" /></td>

      <!-- komórka z przyciskami dodawania i anulowania -->
      <td class="c" colspan="2" style="width: 175px;">
         <button id="add"       class="btn btn-primary">dodaj</button>
         <button id="cancelAdd" class="btn btn-primary">anuluj</button>
      </td>
   </form>
</tr>