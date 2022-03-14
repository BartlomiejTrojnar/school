<!-- **********************  (C) mgr inż. Bartłomiej Trojnar; 27.07.2021 ********************** -->
<?php /*
<h3>historia ucznia</h3>
<table id="studentHistory">
   <tr>
      <th>lp</th>  
      <th>uczeń</th>
      <th>data</th>
      <th>wydarzenie</th>
      <th colspan="2">popraw/<br />usuń</th>
   </tr>

   <?php $count = 0; ?>
   @foreach($studentHistories as $sh)
      <tr>
         <td>{{ ++$count }}</td>
         <td><a href="{{ route('uczen.show', $sh->student_id) }}">
            {{ $sh->student->first_name }} {{ $sh->student->second_name }} {{ $sh->student->last_name }}
         </a></td>  
         <td @if(!$sh->confirmation_date)class="not_confirmation"@endif>{{ $sh->date }}</td>
         <td @if(!$sh->confirmation_event)class="not_confirmation"@endif>{{ $sh->event }}</td> 

         <!-- modyfikowanie i usuwanie -->
         <td class="destroy edit">
            <a class="btn btn-primary" href="{{ route('historia_ucznia.edit', $sh->id) }}"><i class="fa fa-edit"></i></a>
            <input class="idSH" type="hidden" value="{{$sh->id}}" />
            <form action="{{ route('historia_ucznia.destroy', $sh->id) }}" method="post" id="delete-form-{{$sh->id}}">
               {{ csrf_field() }}
               {{ method_field('DELETE') }}
               <button class="btn btn-primary"><i class="fas fa-remove"></i></button>
            </form>
         </td>
      </tr>
   @endforeach

  <tr class="create"><td colspan="6">
      <a class="btn btn-primary" href="{{ route('historia_ucznia.create', 'student_id='.$student->id) }}"><i class="fa fa-plus"></i></a>
  </td></tr>
</table>
*/ ?>