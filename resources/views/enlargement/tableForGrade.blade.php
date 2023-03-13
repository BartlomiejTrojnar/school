<!-- **********************  (C) mgr inż. Bartłomiej Trojnar; 21.01.2023 ********************** -->
<h2>Rozszerzenia w klasie</h2>
<table id="enlargements">
   <thead>
      <tr>
         <th>lp</th>
         <th>uczeń</th>
         <th>rozszerzenie</th>
         <th>poziom</th>
         <th>data wyboru</th>
         <th>data rezygnacji</th>
         <th>zmień / usuń</th>
      </tr>
   </thead>

   <tbody>
      <?php $count=0; ?>
      @foreach($enlargements as $enlargement)
         <tr data-enlargement_id="{{ $enlargement->id }}">
            <td>{{ ++$count }}</td>
            <td>{{ $enlargement->student->first_name }} {{ $enlargement->student->last_name }}</td>
            <td>{{ $enlargement->subject->name }}</td>
            <td>{{ $enlargement->level }}</td>
            <td class="c">{{ $enlargement->choice }}</td>
            <td class="c">{{ $enlargement->resignation }}</td>

            <!-- modyfikowanie i usuwanie -->
            <td class="destroy edit c">
               <button class="edit btn btn-primary"    data-enlargement_id="{{ $enlargement->id }}" data-version="forGrade"><i class="fa fa-edit"></i></button>
               <button class="destroy btn btn-primary" data-enlargement_id="{{ $enlargement->id }}"><i class="fa fa-remove"></i></button>
            </td>
         </tr>
      @endforeach
      <tr class="create"><td colspan="7"><button id="showCreateRow" class="btn btn-primary"><i class="fa fa-plus"></i></button>
   </tbody>
</table>
<input type="hidden" id="countEnlargements" value="{{ count($enlargements) }}" />