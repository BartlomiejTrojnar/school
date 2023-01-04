<tr class="editRow" data-subject_id="{{ $subject->id }}">
<!-- **********************  (C) mgr inż. Bartłomiej Trojnar; 02.01.2023 ********************** -->
   <form action="{{ route('przedmiot.update', $subject->id) }}" method="post" role="form">
      {{ csrf_field() }}
      {{ method_field('PATCH') }}
      <td><var class="lp">{{ $lp }}</var></td>
      <td>
         <input type="hidden" name="id" value="{{ $subject->id }}" />
         <input type="text" name="name" value="{{ $subject->name }}" size="30" maxlength="60" required />
      </td>
      <td><input type="text" name="short_name" value="{{ $subject->short_name }}" size="12" maxlength="15" /></td>
      <td><input type="checkbox" name="actual" @if($subject->actual==1) checked="checked" @endif /></td>
      <td><input type="number" name="order_in_the_sheet" value="{{ $subject->order_in_the_sheet }}" size="2" maxlength="2" /></td>
      <td><input type="checkbox" name="expanded" @if($subject->expanded==1) checked="checked" @endif /></td>
      <!-- komórka z przyciskami potwierdzenia zmiany i anulowania -->
      <td class="c" colspan="3" style="width: 175px;">
         <button class="update btn btn-primary"       data-subject_id="{{ $subject->id }}">zapisz</button>
         <button class="cancelUpdate btn btn-primary" data-subject_id="{{ $subject->id }}">anuluj</button>
      </td>
   </form>
</tr>