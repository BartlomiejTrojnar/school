<tr class="editRow" data-enlargement_id="{{ $enlargement->id }}">
<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 23.01.2023 *********************** -->
   <form action="{{ route('rozszerzenie.update', $enlargement->id) }}" method="post" role="form">
      {{ csrf_field() }}
      {{ method_field('PATCH') }}

      <td>
         <input type="hidden" name="id" value="{{ $enlargement->id }}" />
         <var class="lp">{{ $lp }}</var>
      </td>
      <td><?php  print_r($studentSF);  ?></td>
      <td><?php  print_r($subjectSF);  ?></td>
      <td><input type="text" name="level"       value="{{ $enlargement->level }}" /></td>
      <td><input type="date" name="choice"      value="{{ $enlargement->choice }}" /></td>
      <td><input type="date" name="resignation" value="{{ $enlargement->resignation }}" /></td>

      <!-- komórka z przyciskami potwierdzenia zmiany i anulowania -->
      <td colspan="2" class="c" style="width: 225px;">
         <button class="update btn btn-primary"       data-enlargement_id="{{ $enlargement->id }}" data-version="{{$version}}">zapisz zmiany</button>
         <button class="cancelUpdate btn btn-primary" data-enlargement_id="{{ $enlargement->id }}">anuluj</button>
      </td>
   </form>
</tr>