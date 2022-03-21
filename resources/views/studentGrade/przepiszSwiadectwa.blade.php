<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 07.01.2022 *********************** -->
@extends('layouts.app')

@section('main-content')

Przepisz świadectwa ze starej bazy danych - skrypt w trakcie tworzenia
<?php
   @ $polacz_szkola = mysqli_connect('localhost', 'root', 'ARla575mow');
   //jeżeli połączono - wybór bazy danych
   if($polacz_szkola) {
   	mysqli_query($polacz_szkola, 'SET CHARACTER SET latin2;');
   	mysqli_query($polacz_szkola, 'SET collation_connection = latin2_general_ci;');
   	mysqli_query($polacz_szkola, 'SET character_set_connection=utf8');
   	mysqli_query($polacz_szkola, 'SET character_set_client=utf8');
   	mysqli_query($polacz_szkola, 'SET character_set_results=utf8');
   	mysqli_select_db($polacz_szkola, "szkola");
   }
   @ $polacz_school = mysqli_connect('localhost', 'root', 'ARla575mow');
   mysqli_query($polacz_school, 'SET CHARACTER SET latin2;');
   mysqli_query($polacz_school, 'SET collation_connection = latin2_general_ci;');
   mysqli_query($polacz_school, 'SET character_set_connection=utf8');
   mysqli_query($polacz_school, 'SET character_set_client=utf8');
   mysqli_query($polacz_school, 'SET character_set_results=utf8');
   mysqli_select_db($polacz_school, "school");

   // wyszukanie wpisów nauczania w starej bazie danych
   $wyszukaj_stare_arkusze = "SELECT id_ku, ku.iducznia, newID as uczen_nowy_ID, wzor_arkusza, data_rady FROM klasy_uczniowie ku LEFT JOIN uczniowie u ON ku.iducznia=u.iducznia WHERE wzor_arkusza IS NOT NULL";
   $zestaw_stare_arkusze = mysqli_query($polacz_szkola, $wyszukaj_stare_arkusze);
   echo '<ol>';
	while($stary_arkusz = mysqli_fetch_array($zestaw_stare_arkusze)) {
      printf('<li>%d: uczeń %d, (nowy ID: %d) -> %s [data: %s] </li>', $stary_arkusz['id_ku'], $stary_arkusz['iducznia'], $stary_arkusz['uczen_nowy_ID'], $stary_arkusz['wzor_arkusza'], $stary_arkusz['data_rady']);
      // wyszukanie daty rozpoczęcia nauki dla pierwszej szkolnej klasy ucznia
      $wyszukaj_date_arkusza = "SELECT start FROM student_grades WHERE student_id = '".$stary_arkusz['uczen_nowy_ID']."' ORDER BY start";
      $zestaw_data_arkusza = mysqli_query($polacz_school, $wyszukaj_date_arkusza);
      $data_arkusza = mysqli_fetch_array($zestaw_data_arkusza);
      $data_wystawienia = $data_arkusza['start'];

      // jeżeli data rady jest wcześniejsza niż data wystawienia - zatrzymanie akci w celu sprawdzenia danych
      if($stary_arkusz['data_rady'] !='' && $data_wystawienia > $stary_arkusz['data_rady'])    {
         printf('<li class="btn btn-danger">data arkusza: %s</li>', $data_wystawienia);
         continue;
         printf('<li>-----------------------------------------------------------------------</li>');
      }
      
      // sprawdzenie czy znalezniony został nowy identyfikator ucznia - jeżeli nie - przejście do następnego rekordu
      if($stary_arkusz['uczen_nowy_ID'] == '')    {
         printf('<li>-------------- brak nowego id ucznia ---------------------------------------------------------</li>');
         continue;
      }

      // wstawienie arkusza do nowej bazy danych
      $wstaw_arkusz = "INSERT INTO certificates(`student_id`, `type`, `council_date`, `date_of_issue`, `templates_id`) ";
      $wstaw_arkusz.= "VALUES ('".$stary_arkusz['uczen_nowy_ID']."', 'arkusz', NULL, '".$data_wystawienia."', '".$stary_arkusz['wzor_arkusza']."')";
      mysqli_query($polacz_school, $wstaw_arkusz);

      // usunięcie informacji o arkuszu ze starej bazy danych
      $usun_stary_arkusz = "UPDATE klasy_uczniowie SET wzor_arkusza=NULL WHERE id_ku = '".$stary_arkusz['id_ku']."'";
      mysqli_query($polacz_szkola, $usun_stary_arkusz);


/*
      if($liczba_wpisow==0) {
         printf('<p class="btn btn-info">Brak wpisów historii.</p>');
         $wyszukaj_nowa_historie = "SELECT * FROM student_histories WHERE student_id='".$stara_historia['uczen_nowy_ID']."'";
         $zestaw_nowa_historia = mysqli_query($polacz_school, $wyszukaj_nowa_historie);
         while($nowa_historia = mysqli_fetch_array($zestaw_nowa_historia)) {
            printf('<li>%d: %s -> %s</li>', $nowa_historia['id'], $nowa_historia['date'], $nowa_historia['event']);
         }
         printf('<a href="/school/klasy_uczniow/przepiszHistorie?kasuj&id='.$stara_historia['id_ku'].'">skasuj</a>');
         printf('<a style="margin-left: 150px;" href="/school/uczen/'.$stara_historia['uczen_nowy_ID'].'">idź do ucznia</a>');
      }
      if($liczba_wpisow>1) {
         printf('<p>nowe wpisy: %s</p>', $liczba_wpisow);
         printf('<p class="btn btn-danger">Wiele wpisów historii.</p>');
      }
*/

   };
   echo '</ol>';
?>

@endsection