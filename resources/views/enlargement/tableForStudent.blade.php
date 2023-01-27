<!-- **********************  (C) mgr inż. Bartłomiej Trojnar; 27.01.2023 ********************** -->
<h2>Rozszerzenia w klasie</h2>

<?php $dataWyboru = ""; ?>
<section id="enlargementsSection" style="background: #444; margin: 25px; border-radius: 25px; padding: 10px;">
   <div style="border: 2px solid #000; margin: 20px;">
      @foreach($enlargements as $enlargement)
         @if($dataWyboru != $enlargement->choice)
            @if($dataWyboru != "")
               </div>
               <div style="border: 2px solid #000; margin: 20px;">
            @endif
            <?php $dataWyboru = $enlargement->choice; ?>
            <header style="color: orange; font-size: 150%; margin: 10px auto;">od {{ $enlargement->choice }}</header>
            <ul>
         @endif
         <li data-enlargement_id="{{ $enlargement->id }}">
            {{ $enlargement->subject->name }} {{ $enlargement->level }} {{ $enlargement->resignation }}
            <button class="edit btn btn-primary"    data-enlargement_id="{{ $enlargement->id }}"><i class="fa fa-edit"></i></button>
            <button class="destroy btn btn-primary" data-enlargement_id="{{ $enlargement->id }}"><i class="fa fa-remove"></i></button>
         </li>
      @endforeach
      </ul>
   </div>
   <button id="showCreateForm" class="btn btn-primary" style="width: 80%; margin: 0 10% 25px 10%;"><i class="fa fa-plus"></i></button>
</section>

<table id="enlargements">
   <thead>
      <tr>
         <th>lp</th>
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