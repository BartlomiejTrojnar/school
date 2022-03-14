<div id="createManyExams">
<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 24.11.2021 *********************** -->
   <ul>
      @foreach($examDescriptions as $examDesc)
         <?php $checked=false; ?>
         @if($examDesc->subject->name == 'język polski' && $examDesc->type=='pisemny' && $examDesc->level=='podstawowy') <?php $checked=true; ?> @endif
         @if($examDesc->subject->name == 'język polski' && $examDesc->type=='ustny') <?php $checked=true; ?> @endif
         @if($examDesc->subject->name == 'język angielski' && $examDesc->type=='pisemny' && $examDesc->level=='podstawowy') <?php $checked=true; ?> @endif
         @if($examDesc->subject->name == 'język angielski' && $examDesc->type=='ustny') <?php $checked=true; ?> @endif
         @if($examDesc->subject->name == 'matematyka' && $examDesc->type=='pisemny' && $examDesc->level=='podstawowy') <?php $checked=true; ?> @endif

         <li @if($checked) class="checked" @endif data-exam_description_id="{{$examDesc->id}}">
            {{$examDesc->session->type}} {{$examDesc->session->year}} {{$examDesc->subject->name}} {{$examDesc->type}} {{$examDesc->level}}
         </li>
      @endforeach
   </ul>
   <p>
      <input type="hidden" name="declaration_id" value="{{$declaration_id}}" />
      <button class="btn btn-primary">zapisz</button>
   </p>
</div>