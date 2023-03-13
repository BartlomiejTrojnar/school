<li data-enlargement_id="{{ $enlargement->id }}">
<table class="editForm">
<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 29.01.2023 *********************** -->
   <tr>
      <th>przedmiot</th>
      <th>poziom</th>
      <th>data wyboru</th>
      <th>data rezygnacji</th>
      <td rowspan="2" class="buttons">
         <!-- komórka z przyciskami potwierdzenia zmiany i anulowania -->
         <button class="update btn btn-primary"       data-enlargement_id="{{ $enlargement->id }}">zapisz zmiany</button>
         <button class="cancelUpdate btn btn-primary" data-enlargement_id="{{ $enlargement->id }}">anuluj</button>
      </td>
   </tr>
   <tr class="editRow" data-enlargement_id="{{ $enlargement->id }}">
      <form action="{{ route('rozszerzenie.update', $enlargement->id) }}" method="post" role="form">
         {{ csrf_field() }}
         {{ method_field('PATCH') }}
         <td>
            <input type="hidden" name="id" value="{{ $enlargement->id }}" />
            <input type="hidden" name="student_id" value="{{ $enlargement->student_id }}" />
            <?php  print_r($subjectSF);  ?>
         </td>
         <td><input type="text" name="level"       value="{{ $enlargement->level }}" /></td>
         <td><input type="date" name="choice"      value="{{ $enlargement->choice }}" /></td>
         <td><input type="date" name="resignation" value="{{ $enlargement->resignation }}" /></td>
      </form>
   </tr>
</table>
</li>