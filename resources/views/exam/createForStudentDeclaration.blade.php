<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 18.11.2021 *********************** -->
<tr class="examCreateRow" data-declaration_id="{{$declaration}}">
   <form action="{{ route('egzamin.store') }}" method="post" role="form">
      {{ csrf_field() }}
         <td><?php  print_r($exam_description);  ?></td>
         <td><?php  print_r($examTypeSelectField);  ?></td>
         <td><?php  print_r($termSelectField);  ?></td>
         <td><input type="text" name="comments" size="10" maxlength="15" /></td>
         <td><input type="text" name="points" size="5" maxlength="6" /></td>

         <!-- komórka z przyciskami dodawania i anulowania -->
         <td class="c" colspan="2">
            <input type="hidden" name="declaration_id" value="{{$declaration}}" />
            <button class="btn btn-primary add">dodaj</button>
            <button class="btn btn-primary cancelAdd">anuluj</button>
         </td>
   </form>
</tr>