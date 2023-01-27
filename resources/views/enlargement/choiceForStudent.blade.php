<li data-enlargement_id="{{ $enlargement->id }}">
   <!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 27.01.2023 *********************** -->
   {{ $enlargement->subject->name }} {{ $enlargement->level }} {{ $enlargement->resignation }}
   <!-- przyciski modyfikowania i usuwania -->
   <button class="edit btn btn-primary"    data-enlargement_id="{{ $enlargement->id }}"><i class="fa fa-edit"></i></button>
   <button class="destroy btn btn-primary" data-enlargement_id="{{ $enlargement->id }}"><i class="fa fa-remove"></i></button>
</li>