<tr id="createRow">
<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 25.10.2022 *********************** -->
   <form action="{{ route('certificate.store') }}" method="post" role="form">
      {{ csrf_field() }}
      @if($version=="forStudent")   <td><input type="hidden" name="student_id" value="{{$student}}" /></td> @endif

<?php /*
      @if($version!="forStudent")   <td class="c"><?php  print_r($student);  ?></td>   @endif
      @if($version!="forSession")   <td><?php  print_r($session);  ?></td> @endif
*/ ?>

      <td><input type="text" name="type" size="8" /></td>
      <td><input type="text" name="templates_id" size="8" placeholder="wzór" /></td>
      <td><input type="date" name="council_date" /></td>
      <td><input type="date" name="date_of_issue" /></td>

      <!-- komórka z przyciskami dodawania i anulowania -->
      <td class="c" colspan="2" style="width: 175px;">
         <button id="add" class="btn btn-primary" data-version="{{$version}}">dodaj</button>
         <button id="cancelAdd" class="btn btn-primary">anuluj</button>
      </td>
   </form>
</tr>