<li data-enlargement_id="{{ $enlargement->id }}">
   <!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 29.01.2023 *********************** -->
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