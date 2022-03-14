<!-- ******************  (C) mgr inż. Bartłomiej Trojnar; (III) czerwiec 2021 ****************** -->
<h2>Informacje o szkole</h2>

<table>
   <tr>
      <th>identyfikator OKE</th>
      <td>{{ $school->id_OKE }}</td>
   </tr>
   <tr>
      <th>klasy</th>
      <td>{{ $school->grades->count() }}</td>
   </tr>
   <tr>
      <th>uczniowie (w księdze uczniów)</th>
      <td>{{ $school->students->count() }}</td>
   </tr>
   <tr>
      <th>uczniowie (w klasach)</th>
      <td>
        <?php
          $countStudents = 0;
          foreach($school->grades as $grade) {
            $countStudents += $grade->students->count();
          }
         ?>
         {{$countStudents}}
      </td>
   </tr>
</table>