<!-- **********************  (C) mgr inż. Bartłomiej Trojnar; 19.08.2021 ********************** -->
<h2>rok szkolny {{ substr($schoolYear->date_start, 0, 4) }}/{{ substr($schoolYear->date_end, 0, 4) }}</h2>
<table>
   <tr>
      <th>lp</th>
      <th>data rozpoczęcia</th>
      <th>data zakończenia</th>
      <th>data klasyfikacji ostatnich klas</th>
      <th>data zakończenia nauki ostatnich klas</th>
      <th>data klasyfikacji</th>
      <th>data zakończenia nauki</th>
   </tr>
   <tr>
      <td>{{ $schoolYear->id }}</td>
      <td>{{ $schoolYear->date_start }}</td>
      <td>{{ $schoolYear->date_end }}</td>
      <td>{{ $schoolYear->date_of_classification_of_the_last_grade }}</td>
      <td>{{ $schoolYear->date_of_graduation_of_the_last_grade }}</td>
      <td>{{ $schoolYear->date_of_classification }}</td>
      <td>{{ $schoolYear->date_of_graduation }}</td>
   </tr>
</table>

<p>liczba uczniów: {{ $countStudents }}</p>
<p>liczba klas: {{ $countGrades }}</p>
<p>liczba grup: $countGroups </p>
<p>liczba nauczycieli: $countTeachers</p>