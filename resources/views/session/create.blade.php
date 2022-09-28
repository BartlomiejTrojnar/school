<tr id="createRow">
<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 28.09.2022 *********************** -->
   <form action="{{ route('sesja.store') }}" method="post" role="form">
      {{ csrf_field() }}
      <td></td>
      <td><input type="number" name="year" min="2005" required /></td>
      <td><?php  print_r($typeSF);  ?></td>

      <!-- komórka z przyciskami dodawania i anulowania -->
      <td class="c" colspan="4">
         <button id="add" class="btn btn-primary">dodaj</button>
         <button id="cancelAdd" class="btn btn-primary">anuluj</button>
      </td>
   </form>
</tr>