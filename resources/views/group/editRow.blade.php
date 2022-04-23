<tr class="editRow" data-group_id="{{$group->id}}" style="display: none;">
<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 23.04.2022 *********************** -->
   <form action="{{ route('grupa.update', $group->id) }}" method="post" role="form">
      {{ csrf_field() }}
      {{ method_field('PATCH') }}
      <td colspan="3"><?php  print_r($subjectSelectField);  ?></td>
      <td><?php  print_r($levelSelectField);  ?></td>
      <td><input type="text" name="comments" value="{{$group->comments}}" size="10" maxlength="30" /></td>
      <td>
         <input type="date" name="start" value="{{$group->start}}" required />
         <input type="date" name="end" value="{{$group->end}}" required />
      </td>
      <td><input type="number" name="hours" value="{{$group->hours}}" size="2" maxlength="1" /></td>

      <!-- komórka z przyciskami potwierdzenia zmiany i anulowania -->
      <td colspan="5" class="c">
         <input type="hidden" name="id" value="{{$group->id}}" />
         <button class="update btn btn-primary"       data-group_id="{{$group->id}}" data-version="{{$version}}">zapisz zmiany</button>
         <button class="cancelUpdate btn btn-primary" data-group_id="{{$group->id}}">anuluj</button>
      </td>
   </form>
</tr>