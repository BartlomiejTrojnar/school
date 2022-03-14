<!-- **********************  (C) mgr inż. Bartłomiej Trojnar; 28.07.2021 ********************** -->
<section id="studentHistory">
   <h3>historia ucznia</h3>
   <table>
      <thead>
         <tr>
            <th>data</th>
            <th>wydarzenie</th>
            <th>popraw / usuń</th>
         </tr>
      </thead>

      <tbody>
         @foreach($studentHistory as $sh)
            <tr data-student_history_id="{{ $sh->id }}">
               <td @if(!$sh->confirmation_date)class="not_confirmation"@endif>{{ $sh->date }}</td>
               <td @if(!$sh->confirmation_event)class="not_confirmation"@endif>{{ $sh->event }}</td> 

               <!-- modyfikowanie i usuwanie -->
               <td class="destroy edit c">
                  <button class="edit btn btn-primary"    data-student_history_id="{{ $sh->id }}"><i class="fa fa-edit"></i></button>
                  <button class="destroy btn btn-primary" data-student_history_id="{{ $sh->id }}"><i class="fas fa-remove"></i></button>
               </td>
            </tr>
         @endforeach

         <tr class="create"><td colspan="3">
            <button class="showCreateRow btn btn-primary"><i class="fa fa-plus"></i></button>
         </td></tr>
      </tbody>
   </table>
</section>