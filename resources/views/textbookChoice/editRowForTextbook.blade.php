<tr class="editRow" data-textbookChoice_id="{{$textbookChoice->id}}">
   <form action="{{ route('wybor_podrecznika.update', $textbookChoice->id) }}" method="post" role="form">
   {{ csrf_field() }}
   {{ method_field('PATCH') }}
      <td>
         <input type="hidden" name="id" value="{{$textbookChoice->id}}" />
         <input type="hidden" name="textbook_id" value="{{$textbookChoice->textbook_id}}" />
      </td>
      <td><?php  print_r($schoolSelectField);  ?></td>
      <td><?php  print_r($schoolYearSelectField);  ?></td>
      <td><input type="number" name="learning_year" value="{{$textbookChoice->learning_year}}" min="1" max="4" size="2" required /></td>
      <td><?php  print_r($levelSelectField);  ?></td>

      <!-- komórka z przyciskami potwierdzenia zmiany i anulowania -->
      <td colspan="4" class="c">
         <button class="update btn btn-primary" data-textbookChoice_id="{{$textbookChoice->id}}">zapisz zmiany</button>
         <button class="cancelUpdate btn btn-primary" data-textbookChoice_id="{{$textbookChoice->id}}">anuluj</button>
      </td>
   </form>
</tr>