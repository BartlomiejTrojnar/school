<h2>Plan lekcji dla klasy</h2>
<table>
  <tr>
    <th>godziny</th>
    <th>poniedziałek</th>
    <th>wtorek</th>
    <th>środa</th>
    <th>czwartek</th>
    <th>piątek</th>
  </tr>

  @foreach($lessonHours as $hour)
    <tr>
      <td>{{ $loop->iteration }} {{ substr($hour->start_time, 0, 5) }}</td>
      <td data-hour_id="{{ $hour->id }}"></td>
      <td data-hour_id="{{ $hour->id+9 }}"></td>
      <td data-hour_id="{{ $hour->id+18 }}"></td>
      <td data-hour_id="{{ $hour->id+27 }}"></td>
      <td data-hour_id="{{ $hour->id+36 }}"></td>
    </tr>
  @endforeach
</table>