<!-- **********************  (C) mgr inż. Bartłomiej Trojnar; 16.10.2021 ********************** -->
<tr id="createRow">
   <form action="{{ route('wybor_podrecznika.store') }}" method="post" role="form">
   {{ csrf_field() }}
      <td><input type="hidden" name="textbook_id" value="{{$textbook_id}}" /></td>
      <td><?php  print_r($schoolSelectField);  ?></td>
      <td><?php  print_r($schoolYearSelectField);  ?></td>
      <td><input type="number" name="learning_year" min="1" max="4" size="2" autofocus required /></td>
      <td><?php  print_r($levelSelectField);  ?></td>

      <!-- komórka z przyciskami dodawania i anulowania -->
      <td class="c" colspan="4">
         <button id="add" class="btn btn-primary">dodaj</button>
         <button id="cancelAdd" class="btn btn-primary">anuluj</button>
      </td>
   </form>
</tr>