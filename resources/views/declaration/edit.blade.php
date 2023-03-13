<tr class="editRow" data-declaration_id="{{$declaration->id}}">
<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 10.11.2022 *********************** -->
   <form action="{{ route('deklaracja.update', $declaration->id) }}" method="post" role="form">
      {{ csrf_field() }}
      {{ method_field('PATCH') }}
      
      @if($version=="forIndex" || $version=="forGrade")  <td></td>   @endif
      @if($version=="forStudent")   <td><input type="hidden" name="student_id" value="{{$declaration->student_id}}" /></td> @endif
      @if($version=="forSession")   <td><input type="hidden" name="session_id" value="{{$declaration->session_id}}" /></td> @endif

      @if($version!="forStudent")   <td class="c"><?php  print_r($student);  ?></td>   @endif
      @if($version!="forSession")   <td><?php  print_r($session);  ?></td> @endif
      <td class="c"><input type="number" name="application_number" min="1" max="10" size="2" required value="{{$declaration->application_number}}" /></td>
      <td class="c"><input type="text" name="student_code" size="2" maxlength="3" value="{{$declaration->student_code}}" /></td>

      <!-- komórka z przyciskami potwierdzenia zmiany i anulowania -->
      <td colspan="4" class="c">
         <input type="hidden" name="id" value="{{$declaration->id}}" />
         <button class="update btn btn-primary" data-declaration_id="{{$declaration->id}}" data-version="{{$version}}">zapisz zmiany</button>
         <button class="cancelUpdate btn btn-primary" data-declaration_id="{{$declaration->id}}">anuluj</button>
      </td>
   </form>
</tr>