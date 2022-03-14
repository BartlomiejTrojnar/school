<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 11.11.2021 *********************** -->
<header style="display: none;" class="editTable" data-declaration_id="{{$declaration->id}}">
   <table>
      <tr>
         <th>sesja</th>
         <th>numer zgłoszenia</th>
         <th>kod ucznia</th>
      </tr>

      <form action="{{ route('deklaracja.update', $declaration->id) }}" method="post" role="form">
         {{ csrf_field() }}
         {{ method_field('PATCH') }}
         <tr>
            <td><?php  print_r($session);  ?></td>
            <td class="c"><input type="number" name="application_number" min="1" max="10" size="2" required value="{{$declaration->application_number}}" /></td>
            <td class="c"><input type="text" name="student_code" size="2" maxlength="3" value="{{$declaration->student_code}}" /></td>
         </tr>

         <tr>
            <!-- komórka z przyciskami potwierdzenia zmiany i anulowania -->
            <td colspan="3" class="c">
               <input type="hidden" name="student_id" value="{{$declaration->student_id}}" />
               <input type="hidden" name="id" value="{{$declaration->id}}" />
               <button class="update btn btn-primary"       data-declaration_id="{{$declaration->id}}">zapisz zmiany</button>
               <button class="cancelUpdate btn btn-primary" data-declaration_id="{{$declaration->id}}">anuluj</button>
            </td>
         </tr>
      </form>
   </table>
</header>