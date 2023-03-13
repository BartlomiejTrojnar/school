<!-- **********************  (C) mgr inż. Bartłomiej Trojnar; 29.01.2023 ********************** -->
<h2>rozszerzenia wybrane przez ucznia</h2>

<?php $dataWyboru = ""; ?>
<section id="enlargementsSection">
      @foreach($enlargements as $enlargement)
         @if($dataWyboru != $enlargement->choice)
            @if($dataWyboru != "")
               </div>
            @endif
            <?php $dataWyboru = $enlargement->choice; ?>
            <div data-choice="{{ $enlargement->choice }}">
               <header>od <time datetime="{{ $enlargement->choice }}">{{ $enlargement->choice }}</time></header>
               <ul>
         @endif
         <li data-enlargement_id="{{ $enlargement->id }}">
            @if($enlargement->resignation)
               <del>
                  {{ $enlargement->subject->name }} {{ $enlargement->level }} {{ $enlargement->resignation }}
               </del>
            @else
               {{ $enlargement->subject->name }} {{ $enlargement->level }} {{ $enlargement->resignation }}
               <button class="exchange btn btn-primary" data-enlargement_id="{{ $enlargement->id }}"><i class="fa fa-exchange"></i></button>
            @endif
            <button class="edit btn btn-primary"    data-enlargement_id="{{ $enlargement->id }}"><i class="fa fa-edit"></i></button>
            <button class="destroy btn btn-primary" data-enlargement_id="{{ $enlargement->id }}"><i class="fa fa-remove"></i></button>
         </li>
      @endforeach
      </ul>
   </div>
   <button id="showCreateForm" class="btn btn-primary" style="width: 80%; margin: 0 10% 25px 10%;"><i class="fa fa-plus"></i></button>
</section>