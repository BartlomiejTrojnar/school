<tr class="editRow" data-school_id="{{ $school->id }}">
<!-- **********************  (C) mgr inż. Bartłomiej Trojnar; 10.01.2023 ********************** -->
   <form action="{{ route('szkola.update', $school->id) }}" method="post" role="form">
      {{ csrf_field() }}
      {{ method_field('PATCH') }}
      <td><var class="lp">{{ $lp }}</var></td>
      <td>
         <input type="hidden" name="id" value="{{ $school->id }}" />
         <input type="text" name="name" value="{{ $school->name }}" size="40" required />
      </td>
      <td><input type="text" name="id_OKE" value="{{ $school->id_OKE }}" /></td>

      <!-- komórka z przyciskami potwierdzenia zmiany i anulowania -->
      <td class="c" style="width: 225px;">
         <button class="update btn btn-primary"       data-school_id="{{ $school->id }}">zapisz zmiany</button>
         <button class="cancelUpdate btn btn-primary" data-school_id="{{ $school->id }}">anuluj</button>
      </td>
   </form>
</tr>