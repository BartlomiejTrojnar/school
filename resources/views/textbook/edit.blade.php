<!-- **********************  (C) mgr inż. Bartłomiej Trojnar; 13.10.2021 ********************** -->
<tr class="editRow" data-textbook_id="{{$textbook->id}}">
   <form action="{{ route('podrecznik.update', $textbook->id) }}" method="post" role="form">
      {{ csrf_field() }}
      {{ method_field('PATCH') }}
      <td colspan="2">
         <input type="hidden" name="id" value="{{$textbook->id}}" />
         <input type="hidden" name="lp" value="{{$lp}}" />
         <?php  print_r($subjectSelectField);  ?>
      </td>
      <td><textarea name="author" rows="3" cols="20" required>{{$textbook->author}}</textarea></td>
      <td><textarea name="title" rows="3" cols="20" required>{{$textbook->title}}</textarea></td>
      <td><input type="text" name="publishing_house" value="{{$textbook->publishing_house}}" size="15" maxlength="30" /></td>
      <td><input type="text" name="admission" value="{{$textbook->admission}}" size="18" maxlength="20" /></td>
      <td><input type="text" name="comments" value="{{$textbook->comments}}" size="20" maxlength="60" /></td>

      <!-- komórka z przyciskami potwierdzenia zmiany i anulowania -->
      <td class="c" colspan="3" style="width: 225px;">
         <button class="update btn btn-primary" data-textbook_id="{{$textbook->id}}">zapisz</button>
         <button class="cancelUpdate btn btn-primary" data-textbook_id="{{$textbook->id}}">anuluj</button>
      </td>
   </form>
</tr>
