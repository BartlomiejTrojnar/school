<tr id="createRow">
<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 26.10.2022 *********************** -->
   <form action="{{ route('certificate.store') }}" method="post" role="form">
      {{ csrf_field() }}
      @if($version=="forStudent")   <td><input type="hidden" name="student_id" value="{{$student}}" /></td> @endif

      <td><?php echo $typeSF; ?></td>
      <td><?php echo $templateSF; ?></td>
      <td><input type="date" name="council_date" /></td>
      <td><input type="date" name="date_of_issue" /></td>

      <!-- komórka z przyciskami dodawania i anulowania -->
      <td class="c" colspan="2" style="width: 175px;">
         <button id="add" class="btn btn-primary" data-version="{{$version}}">dodaj</button>
         <button id="cancelAdd" class="btn btn-primary">anuluj</button>
      </td>
   </form>
</tr>