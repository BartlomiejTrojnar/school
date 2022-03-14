<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 06.11.2021 *********************** -->
<tr id="createRow">
   <form action="{{ route('deklaracja.store') }}" method="post" role="form">
      {{ csrf_field() }}

      @if($version=="forIndex" || $version=="forGrade")  <td></td>   @endif
      @if($version=="forStudent")   <td><input type="hidden" name="student_id" value="{{$student}}" /></td> @endif
      @if($version=="forSession")   <td><input type="hidden" name="session_id" value="{{$session}}" /></td> @endif

      @if($version!="forStudent")   <td class="c"><?php  print_r($student);  ?></td>   @endif
      @if($version!="forSession")   <td><?php  print_r($session);  ?></td> @endif
      <td class="c"><input type="number" name="application_number" min="1" max="10" size="3" required /></td>
      <td class="c"><input type="text" name="student_code" size="2" maxlength="3" /></td>

      <!-- komórka z przyciskami dodawania i anulowania -->
      <td class="c" colspan="4">
         <button id="add" class="btn btn-primary" data-version="{{$version}}">dodaj</button>
         <button id="cancelAdd" class="btn btn-primary">anuluj</button>
      </td>
   </form>
</tr>