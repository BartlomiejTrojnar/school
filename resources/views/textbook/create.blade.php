<!-- **********************  (C) mgr inż. Bartłomiej Trojnar; 13.10.2021 ********************** -->
<tr id="createRow">
   <form action="{{ route('podrecznik.store') }}" method="post" role="form">
      {{ csrf_field() }}
      <td colspan="2"><?php  print_r($subjectSelectField);  ?></td>
      <td><textarea name="author" required></textarea></td>
      <td><textarea name="title" required></textarea></td>
      <td><input type="text" name="publishing_house" size="15" maxlength="30" /></td>
      <td><input type="text" name="admission" size="18" maxlength="20" /></td>
      <td colspan="2"><input type="text" name="comments" size="40" maxlength="60" /></td>

      <!-- komórka z przyciskami dodawania i anulowania -->
      <td class="c" colspan="2">
         <button id="add" class="btn btn-primary">dodaj</button>
         <button id="cancelAdd" class="btn btn-primary">anuluj</button>
      </td>
   </form>
</tr>