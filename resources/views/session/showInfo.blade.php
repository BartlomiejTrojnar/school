<h2>Informacje o sesji</h2>
<table>
  <tr>
    <th>rok</th>
    <td>{{ $session->year }}</td>
  </tr>
  <tr>
    <th>miesiąc</th>
    <td>{{ $session->type }}</td>
  </tr>
  <tr>
    <th>liczba deklaracji</th>
    <td><a class="nav-link" href="{{ url('sesja/'.$session->id.'/showDeclarations') }}">{{ count($session->declarations )}}</a></td>
  </tr>
  <tr>
    <th>liczba typów egzaminów</th>
    <td><a class="nav-link" href="{{ url('sesja/'.$session->id.'/showExamDescriptions') }}">{{ count($session->examDescriptions) }}</a></td>
  </tr>
  <tr>
    <th>liczba terminów</th>
    <?php 
      $countTerms=0;
      foreach($session->examDescriptions as $ed)
        $countTerms += count($ed->terms);
    ?>
    <td><a class="nav-link" href="{{ url('sesja/'.$session->id.'/showTerms') }}">{{ $countTerms }}</a></td>
  </tr>
  <tr>
    <th>liczba egzaminów</th>
    <td><em>do realizacji</em></td>
  </tr>
</table>
