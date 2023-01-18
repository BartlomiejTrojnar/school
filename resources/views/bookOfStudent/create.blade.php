<tr class="create" id="createRow" style="display: none;">
<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 18.01.2023 *********************** -->
   <form action="{{ route('ksiega_uczniow.store') }}" method="post" role="form">
      {{ csrf_field() }}
      <td colspan="2"><?php   print_r($schoolSF);  ?></td>
      <td><?php   print_r($studentSF);  ?></td>
      <td><input type="number" name="number" min="1" required value="{{ $proposedNumber }}" /></td>

      <td colspan="4">
         <button id="add" class="btn btn-primary">dodaj</button>
         <button id="cancelAdd" class="btn btn-primary">anuluj</button>
      </td>
   </form>
</tr>