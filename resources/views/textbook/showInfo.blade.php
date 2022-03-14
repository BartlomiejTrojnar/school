<!-- **********************  (C) mgr inż. Bartłomiej Trojnar; 13.10.2021 ********************** -->
<h2>Informacje o podręczniku</h2>
<table>
   <tr>
      <th>przedmiot</th>
      <td><a href="{{ route('przedmiot.show', $textbook->subject_id) }}">{{ $textbook->subject->name }}</a></td>
   </tr>
   <tr>
      <th>autor</th>
      <td>{{ $textbook->author }}</td>
   </tr>
   <tr>
      <th>nazwa</th>
      <td>{{ $textbook->title }}</td>
   </tr>
   <tr>
      <th>wydawnictwo</th>
      <td>{{ $textbook->publishing_house }}</td>
   </tr>
   <tr>
      <th>nr dopuszczenia</th>
      <td>{{ $textbook->admission }}</td>
   </tr>
   <tr>
      <th>uwagi</th>
      <td>{{ $textbook->comments }}</td>
   </tr>
   <tr>
      <th>wybrano</th>
      <td>{{ count($textbook->textbookChoices) }} razy</td>
   </tr>
   <tr>
      <th>wpisano</th>
      <td>{{ $textbook->created_at }}</td>
   </tr>
   <tr>
      <th>ostatnia modyfikacja</th>
      <td>{{ $textbook->updatet_at }}</td>
   </tr>
</table>

<?php echo $textbookChoicesTable; ?>