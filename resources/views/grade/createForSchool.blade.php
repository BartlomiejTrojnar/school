<!-- ******************  (C) mgr inż. Bartłomiej Trojnar; (III) czerwiec 2021 ****************** -->
<tr id="createRow">
   <form action="{{ route('klasa.store') }}" method="post" role="form">
      {{ csrf_field() }}
      <td colspan="2">
         <input type="number" name="year_of_beginning" placeholder="2021" min="2020" required autofocus />
         <input type="number" name="year_of_graduation" placeholder="2022" min="2022" required />
         <input type="text" name="symbol" size="1" maxlength="2" placeholder="a" />
         <input type="hidden" name="school_id" value="{{$school_id}}" />
      </td>

      <!-- komórka z przyciskami dodawania i anulowania -->
      <td class="c" style="width: 175px;">
         <button id="add" class="btn btn-primary">dodaj</button>
         <button id="cancelAdd" class="btn btn-primary">anuluj</button>
      </td>
   </form>
</tr>