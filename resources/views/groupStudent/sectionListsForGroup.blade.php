<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 02.05.2022 *********************** -->
<div>
   <h2 style="display: inline-block;">stan dla daty: <input type="date" id="dateView" value="{{ $dateView }}" /></h2>
   <p style="display: inline-block;"><datalist></datalist> istnienia grupy: <span id="groupStart">{{ $group->start }}</span> - <span id="groupEnd">{{ $group->end }}</span></p>
   <span id="groupGrades" style="margin-left: 50px;">
   @foreach($group->grades as $gg)
      <button class="btn on" data-grade_id="{{$gg->grade->id}}">{{ $year - $gg->grade->year_of_beginning }}{{ $gg->grade->symbol }}</button>
   @endforeach
   </span>
</div>

<p id="buttons">
   <button class="btn btn-primary" id="addAllStudents">dodaj wszystkich</button>
   <button class="btn btn-primary" id="addCheckedStudents">dodaj zaznaczonych</button>
</p>

<div class="col-md-6">
   <h2>uczniowie grupy: <span id="countStudents"></span></h2>
   <?php echo $listGroupStudents; ?>

   <h3>w innym terminie w grupie</h3>
   <?php echo $listGroupStudentsInOtherTime; ?>
</div>

<div class="col-md-5">
   <h3>Pozostali uczniowie klasy</h3>
   <?php echo $listOutsideGroupStudents; ?>
</div>