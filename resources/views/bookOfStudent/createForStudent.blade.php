<aside class="createForm" style="position: absolute; left: 450px; top: 180px; border: 5px solid #ff3;">
<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 05.01.2022 *********************** -->
   <form action="{{ route('ksiega_uczniow.store') }}" method="post" role="form">
      {{ csrf_field() }}
      <table>
         <tr>
            <th><label for="school_id">szkoła</label></th>
            <td><?php  print_r($schoolSelectField);  ?></td>
         </tr>
         <tr>
            <th><label for="number">numer</label></th>
            <td><input type="number" name="number" min="1" required value="{{ $proposedNumber }}" /></td>
         </tr>

         <tr class="submit"><td colspan="2">
            <input type="hidden" name="student_id" value="{{ $student_id }}" />
            <button class="add btn btn-primary">dodaj</button>
            <button class="cancelAdd btn btn-primary">anuluj</button>
         </td></tr>
      </table>
   </form>
</aside>