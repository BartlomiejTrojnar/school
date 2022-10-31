<!-- **********************  (C) mgr inż. Bartłomiej Trojnar; 31.10.2022 ********************** -->
<h2>Ocent w klasie</h2>
<table id="ratings">
   <thead>
      <tr>
         <th>uczeń</th>
         <th>rozszerzenie</th>
         <th>poziom</th>
         <th>data wyboru</th>
         <th>data rezygnacji</th>
      </tr>
   </thead>

   <tbody>
      <tr><td>Czekamy na realizację!</td></tr>
      @foreach($enlargements as $enlargement)
         <tr>
            <td>{{ $enlargement->student->first_name }} {{ $enlargement->student->last_name }}</td>
            <td>{{ $enlargement->subject->name }}</td>
            <td>{{ $enlargement->level }}</td>
            <td>{{ $enlargement->choice }}</td>
            <td>{{ $enlargement->resignation }}</td>
         </tr>
      @endforeach
   </tbody>
</table>