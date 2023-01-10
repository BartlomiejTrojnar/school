<tr class="editRow" data-classroom_id="{{ $classroom->id }}">
<!-- **********************  (C) mgr inż. Bartłomiej Trojnar; 10.01.2023 ********************** -->
   <form action="{{ route('sala.update', $classroom->id) }}" method="post" role="form">
      {{ csrf_field() }}
      {{ method_field('PATCH') }}
      <td colspan="2">
         <input type="hidden" name="id" value="{{ $classroom->id }}" />
         <var class="lp">{{ $lp }}</var>
         <input type="text" name="name" value="{{ $classroom->name }}" size="15" maxlength="20" />
      </td>
      <td><input type="number" name="capacity" value="{{ $classroom->capacity }}" size="3" maxlength="3" /></td>
      <td><input type="number" name="floor" value="{{ $classroom->floor }}" size="2" maxlength="1" /></td>
      <td><input type="number" name="line" value="{{ $classroom->line }}" size="2" maxlength="2" /></td>
      <td><input type="number" name="column" value="{{ $classroom->column }}" size="2" maxlength="2" /></td>

      <!-- komórka z przyciskami potwierdzenia zmiany i anulowania -->
      <td class="c" style="width: 225px;">
         <button class="update btn btn-primary"       data-classroom_id="{{ $classroom->id }}">zapisz zmiany</button>
         <button class="cancelUpdate btn btn-primary" data-classroom_id="{{ $classroom->id }}">anuluj</button>
      </td>
   </form>
</tr>