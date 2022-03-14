<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 30.10.2021 *********************** -->
<tr id="createRow">
   <form action="{{ route('opis_egzaminu.store') }}" method="post" role="form">
      {{ csrf_field() }}
      <td></td>
      <td><?php  print_r($sessionSelectField);  ?></td>
      <td><?php  print_r($subjectSelectField);  ?></td>
      <td><?php  print_r($examTypeSelectField);  ?></td>
      <td><?php  print_r($levelSelectField);  ?></td>
      <td><input type="number" name="max_points" min="1" max="100" size="4" /></td>
      
      <!-- komórka z przyciskami dodawania i anulowania -->
      <td class="c" colspan="3">
         <button id="add" class="btn btn-primary">dodaj</button>
         <button id="cancelAdd" class="btn btn-primary">anuluj</button>
      </td>
   </form>
</tr>