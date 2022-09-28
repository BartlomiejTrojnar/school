<tr class="editRow" data-session_id="{{$session->id}}">
<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 28.09.2022 *********************** -->
   <form action="{{ route('sesja.update', $session->id) }}" method="post" role="form">
      {{ csrf_field() }}
      {{ method_field('PATCH') }}
      <td></td>
      <td><input type="number" name="year" min="2005" required value="{{ $session->year }}" /></td>
      <td><?php  print_r($typeSF);  ?></td>
      
      <!-- komórka z przyciskami potwierdzenia zmiany i anulowania -->
      <td colspan="4" class="c">
         <button data-session_id="{{$session->id}}" class="update btn btn-primary">zapisz zmiany</button>
         <button data-session_id="{{$session->id}}" class="cancelUpdate btn btn-primary">anuluj</button>
      </td>
   </form>
</tr>