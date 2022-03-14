<table>
  <thead>
    <tr>
      <th>księga</th>
      <th>klasa</th>
      <th>nr</th>
      <th>grupa</th>
      <th>imię</th>
      <th>nazwisko</th>
      <th>termin zadania</th>
      <th>data wykonania</th>
      <th>uwagi</th>
      <th style="width: 5;">wersja</th>
      <th style="width: 5;">waga</th>
      <th style="width: 5;">punkty</th>
      <th style="width: 5;">% punktów</th>
      <th style="width: 5;">Ocena</th>
      <th style="width: 5;">Data oceny</th>
      <th style="width: 5;">Data dziennika</th>

      @foreach($commands as $command)
        <th style="background: #ff8800; font-style: italic; width: 5;">{{ $command->command }}</th>
      @endforeach

    </tr>
  </thead>
</table>