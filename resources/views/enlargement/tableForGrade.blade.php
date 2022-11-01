<!-- **********************  (C) mgr inż. Bartłomiej Trojnar; 31.10.2022 ********************** -->
<h2>Ocent w klasie</h2>
<table id="enlargements">
   <thead>
      <tr>
         <th>uczeń</th>
         <th>rozszerzenie</th>
         <th>poziom</th>
         <th>data wyboru</th>
         <th>data rezygnacji</th>
         <th>zmień / usuń</th>
      </tr>
   </thead>

   <tbody>
      @foreach($enlargements as $enlargement)
         <tr data-enlargement_id="{{ $enlargement->id }}">
            <td>{{ $enlargement->student->first_name }} {{ $enlargement->student->last_name }}</td>
            <td>{{ $enlargement->subject->name }}</td>
            <td>{{ $enlargement->level }}</td>
            <td>{{ $enlargement->choice }}</td>
            <td>{{ $enlargement->resignation }}</td>

            <!-- modyfikowanie i usuwanie -->
            <td class="destroy edit c">
               <button class="edit btn btn-primary"    data-enlargement_id="{{ $enlargement->id }}" data-version="forGrade"><i class="fa fa-edit"></i></button>
               <button class="destroy btn btn-primary" data-enlargement_id="{{ $enlargement->id }}"><i class="fa fa-remove"></i></button>
            </td>
         </tr>
      @endforeach
   </tbody>
</table>