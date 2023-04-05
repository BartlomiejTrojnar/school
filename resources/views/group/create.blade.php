<tr id="createRow">
<!-- **********************  (C) mgr inż. Bartłomiej Trojnar; 28.03.2023 ********************** -->
   <form action="{{ route('grupa.store') }}" method="post" role="form">
   {{ csrf_field() }}
      <td colspan="3"><?php  print_r($subjectSF); ?></td>
      <td><?php  print_r($levelSF);  ?></td>
      <td><input type="text" name="comments" size="10" maxlength="30" /></td>
      <td class="c">
         <input id="dateStart" type="date" name="start" required /><br />
         <button class="btn btn-primary proposedGradeDateStart" style="font-size: 0.8em; padding: 10px;">{{ date('Y-m-d') }}</button>
         <button class="btn btn-primary proposedGradeDateStart" style="font-size: 0.8em; padding: 10px;">{{ $proposedDates['dateOfStartSchoolYear'] }}</button>
      </td>
      <td class="c">
         <input id="dateEnd" type="date" name="end" required /><br />
         <button class="btn btn-primary proposedGradeDateEnd" style="font-size: 0.8em; padding: 10px;">{{ $proposedDates['dateOfGraduationOfTheLastGrade'] }}</button>
         <button class="btn btn-primary proposedGradeDateEnd" style="font-size: 0.8em; padding: 10px;">{{ $proposedDates['dateOfGraduationSchoolYear'] }}</button>
      </td>
      <td><input type="number" name="hours" size="2" maxlength="1" /></td>

      @if(!empty($teacher))
            <th><label for="teacher_id">grupa dla:</label></th>
            <td class="c"><input type="hidden" name="teacher_id" value="{{$teacher->id}}" />{{$teacher->first_name}} {{$teacher->last_name}}</td>
      @else
            <td><input type="hidden" name="teacher_id" value="0" /></td>
      @endif

      <!-- komórka z przyciskami dodawania i anulowania -->
      <td class="c" colspan="4" style="width: 175px;">
         <button id="add" class="btn btn-primary">dodaj</button>
         <button id="cancelAdd" class="btn btn-primary">anuluj</button>
      </td>
   </form>
</tr>