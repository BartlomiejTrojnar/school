<table id="createForm">
<!-- **********************  (C) mgr inż. Bartłomiej Trojnar; 23.01.2023 ********************** -->
   <tr>
      <th>przedmiot</th>
      <th>poziom</th>
      <th>data wyboru</th>
      <th>data rezygnacji</th>
      <td rowspan="2">
         <!-- przyciski dodawania i anulowania -->
         <button id="add"       class="btn btn-primary">dodaj</button>
         <button id="cancelAdd" class="btn btn-primary">anuluj</button>
      </td>
   </tr>
   <tr>
      <form action="{{ route('rozszerzenie.store') }}" method="post" role="form">
         {{ csrf_field() }}
         <td><?php  print_r($subjectSF);  ?></td>
         <td><input type="text" name="level" size="12" maxlength="12" require /></td>
         <td><input type="date" name="choice" /></td>
         <td><input type="date" name="resignation" /></td>
      </form>
   </tr>
</table>