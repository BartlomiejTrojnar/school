<!--------------------- (C) mgr inż. Bartłomiej Trojnar; (I) kwiecień 2021 ---------------------->
<section id="studentGroupsSection">
   <p><strong>Uczeń należy do grup</strong> (stan na <input type="date" id="dateView" value="{{ $dateView }}" /> )</p>
   <p class="hidden"><input name="student_id" value="{{$student_id}}" /></p>
   <?php echo $studentList; ?>

   <p><strong>Grupy do których uczeń należał</strong></p>
   <?php echo $studentListOutside; ?>
</section>

<section id="otherGroupsSection">
   <p><strong>Inne grupy w aktualnej klasie</strong></p>
   <?php echo $otherGroupsInGrade; ?>
</section>