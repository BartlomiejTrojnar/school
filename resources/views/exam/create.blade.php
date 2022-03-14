<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 20.11.2021 *********************** -->
<tr id="createRow">
   <form action="{{ route('egzamin.store') }}" method="post" role="form">
      {{ csrf_field() }}
      @if($version=="forDeclaration")  <td><input type="hidden" name="declaration_id" value="{{$declaration}}" /></td>  @endif
      @if($version=="forExamDescription") <td><input type="hidden" name="exam_description_id" value="{{$exam_description}}" /></td> @endif

      @if($version!="forDeclaration") <td><?php  print_r($declaration);  ?></td> @endif
      @if($version!="forExamDescription") <td><?php  print_r($exam_description);  ?></td> @endif

      <td><?php  print_r($termSelectField);  ?></td>
      <td><?php  print_r($examTypeSelectField);  ?></td>
      <td><input type="text" name="points" size="5" maxlength="6" /></td>
      <td><input type="text" name="comments" size="10" maxlength="15" /></td>

      <!-- komórka z przyciskami dodawania i anulowania -->
      <td class="c" colspan="3">
         <button id="add" class="btn btn-primary" data-version="{{$version}}">dodaj</button>
         <button id="cancelAdd" class="btn btn-primary">anuluj</button>
      </td>
   </form>
</tr>