<tr id="createRow">
<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 28.09.2022 *********************** -->
   <form action="{{ route('opis_egzaminu.store') }}" method="post" role="form">
      {{ csrf_field() }}
      <td></td>
      <td><?php  print_r($sessionSF);  ?></td>
      <td><?php  print_r($subjectSF);  ?></td>
      <td><?php  print_r($examTypeSF);  ?></td>
      <td><?php  print_r($levelSF);  ?></td>
      <td><input type="number" name="max_points" min="1" max="100" size="4" /></td>
      
      <!-- komórka z przyciskami dodawania i anulowania -->
      <td class="c" colspan="4">
         <button id="add"        class="btn btn-primary">dodaj</button>
         <button id="cancelAdd"  class="btn btn-primary">anuluj</button>
      </td>
   </form>
</tr>