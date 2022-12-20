<!-- **********************  (C) mgr inż. Bartłomiej Trojnar; 20.12.2021 ********************** -->
<tr class="editRow" data-grade_id="{{$grade->id}}">
   <form action="{{ route('klasa.update', $grade->id) }}" method="post" role="form">
      {{ csrf_field() }}
      {{ method_field('PATCH') }}
      <td colspan="2">
         <input type="hidden" name="id" value="{{$grade->id}}" />
         <input type="hidden" name="lp" value="{{$lp}}" />
         <input type="number" name="year_of_beginning" value="{{ $grade->year_of_beginning }}" size="5" maxlength="4" />
         <input type="number" name="year_of_graduation" value="{{ $grade->year_of_graduation }}" size="5" maxlength="4" />
         <input type="text" name="symbol" value="{{ $grade->symbol }}" size="1" maxlength="2" />
      </td>
      <td><?php  print_r($schoolSelectField);  ?></td>

      <!-- komórka z przyciskami potwierdzenia zmiany i anulowania -->
      <td class="c" style="width: 225px;">
         <button class="update btn btn-primary" data-grade_id="{{$grade->id}}">zapisz zmiany</button>
         <button class="cancelUpdate btn btn-primary" data-grade_id="{{$grade->id}}">anuluj</button>
      </td>
   </form>
</tr>