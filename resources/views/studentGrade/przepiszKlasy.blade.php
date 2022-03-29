<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 07.01.2022 *********************** -->
@extends('layouts.app')

@section('main-content')

<h1>Sprawdź i przepisz numery ze starej bazy danych - skrypt w trakcie tworzenia</h1>
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
   $wyszukaj_stara_klasa = "SELECT id_ku, ku.iducznia, u.newID as uczen_nowy_ID, ku.idklasy, k.newID as klasa_nowy_ID, data_od, data_do FROM klasy_uczniowie ku LEFT JOIN uczniowie u ON ku.iducznia=u.iducznia   LEFT JOIN klasy k ON ku.idklasy=k.idklasy   WHERE ku.idklasy IS NOT NULL";
   printf('<p>%s</p>', $wyszukaj_stara_klasa);
   $zestaw_stara_klasa = mysqli_query($polacz_szkola, $wyszukaj_stara_klasa);
   echo '<ol>';
   while($klasa = mysqli_fetch_array($zestaw_stara_klasa)) {
      printf('<li>%d: uczeń %d, (nowy ID: <a href="/school/uczen/%d">%d</a>) -> klasa %s (nowy id: %s) [daty: %s : %s] </li>',
         $klasa['id_ku'], $klasa['iducznia'], $klasa['uczen_nowy_ID'], $klasa['uczen_nowy_ID'],
         $klasa['idklasy'], $klasa['klasa_nowy_ID'], $klasa['data_od'], $klasa['data_do']);

      $data_do = $klasa['data_do'];
      if($data_do=='2002-06-30')   $data_do='2002-08-31';

      // sprawdzenie czy w nowej bazie danych jest odpowiedni rekord
      $sprawdz_klase = "SELECT count(id) FROM student_grades WHERE student_id='".$klasa['uczen_nowy_ID']."' AND grade_id='".$klasa['klasa_nowy_ID']."' AND start='".$klasa['data_od']."' AND end='".$data_do."'";
      //printf('<p>%s</p>', $sprawdz_klase);
      $zestaw_sprawdz_klase = mysqli_query($polacz_school, $sprawdz_klase);
      $licz_klasy = mysqli_fetch_array($zestaw_sprawdz_klase);
      if($licz_klasy[0]==1) {
         //kasuj stary numer
         $kasuj_klase = "UPDATE klasy_uczniowie SET idklasy=NULL WHERE id_ku='".$klasa['id_ku']."'";
         printf('<p class="btn btn-info">%s</p>', $kasuj_klase);
         mysqli_query($polacz_szkola, $kasuj_klase);
      }
      if($licz_klasy[0]==0) {
         // sprawdzenie czy jest inny wpis dotyczący ucznia i klasy
         $sprawdz_klase2 = "SELECT * FROM student_grades WHERE student_id='".$klasa['uczen_nowy_ID']."' AND grade_id='".$klasa['klasa_nowy_ID']."'";
         $zestaw_sprawdz_klase2 = mysqli_query($polacz_school, $sprawdz_klase2);
         $licz_klasy2 = $zestaw_sprawdz_klase2 -> num_rows;
         if($licz_klasy2==0) {
            //wpisz klase do bazy
            printf('<p class="btn btn-info">brak wpisów - wpisz klase</p>');
            $wpisz_klase = "INSERT INTO student_grades (student_id, grade_id, start, end, confirmation_start, confirmation_end) ";
            $wpisz_klase .= "VALUES ('".$klasa['uczen_nowy_ID']."', '".$klasa['klasa_nowy_ID']."', '".$klasa['data_od']."', '".$klasa['data_do']."', 0, 0)";
            mysqli_query($polacz_school, $wpisz_klase);
         }
         if($licz_klasy2==1) {
            printf('<h5>już wpisane:</h5><ul>');
            $klasa_nowy = mysqli_fetch_array($zestaw_sprawdz_klase2);
            printf('<li>klasa %s od %s do %s</li>', $klasa_nowy['grade_id'], $klasa_nowy['start'], $klasa_nowy['end']);
            printf('</ul>');

            //usunięcie wpisu jeżeli w nowej bazie wpis już zawiera ten stary
            if($klasa_nowy['start']<=$klasa['data_od']  &&  $klasa_nowy['end']>=$klasa['data_do']) {
               printf('<p class="btn btn-danger">USUWAM stary wpis</p>');
               $usun_stary_wpis = "DELETE FROM klasy_uczniowie WHERE id_ku='".$klasa['id_ku']."'";
               mysqli_query($polacz_szkola, $usun_stary_wpis);
            }
            else {
               if($klasa_nowy['end'] == date('Y-m-d', strtotime('-1 day', strtotime($klasa['data_od']))) ) {
                  printf('<p class="btn btn-primary">Aktualizacja dziada</p>');
                  $aktualizacja_klasy = "UPDATE student_grades SET end = '".$klasa['data_do']."' WHERE id='".$klasa_nowy['id']."'";
                  mysqli_query($polacz_school, $aktualizacja_klasy);
                  $usun_stary_wpis = "DELETE FROM klasy_uczniowie WHERE id_ku='".$klasa['id_ku']."'";
                  mysqli_query($polacz_szkola, $usun_stary_wpis);
               }
               else {
                  printf('<p class="btn btn-warning">wpisz dziada; %s, %s</p>', $klasa_nowy['end'], date('Y-m-d', strtotime('-1 day', strtotime($klasa['data_od']))));
                  if($klasa_nowy['end']<$klasa['data_od']) {
                     $wpisz_klase = "INSERT INTO student_grades (student_id, grade_id, start, end, confirmation_start, confirmation_end) ";
                     $wpisz_klase .= "VALUES ('".$klasa['uczen_nowy_ID']."', '".$klasa['klasa_nowy_ID']."', '".$klasa['data_od']."', '".$klasa['data_do']."', 0, 0)";
                     mysqli_query($polacz_school, $wpisz_klase);
                     $usun_stary_wpis = "DELETE FROM klasy_uczniowie WHERE id_ku='".$klasa['id_ku']."'";
                     mysqli_query($polacz_szkola, $usun_stary_wpis);   
                  }
                  else {
                     printf('<p class="btn btn-primary">Dziad jest wcześniejszy niż już wpisany!!!</p>');
                  }
               }   
            }
         }
         if($licz_klasy2>1) {
            //wpisz klase do bazy
            printf('<p class="btn btn-danger">Wpis wielokrotny</p>');
            while($klasa_nowy = mysqli_fetch_array($zestaw_sprawdz_klase2))
               printf('<li>klasa %s od %s do %s</li>', $klasa_nowy['grade_id'], $klasa_nowy['start'], $klasa_nowy['end']);
         }


         //$wpisz_klase = "INSERT INTO student_numbers (student_id, grade_id, start, end) VALUES ('".$klasa['uczen_nowy_ID']."', '".$klasa['klasa_nowy_ID']."', '".$klasa['data_od']."', '".$data_do."')";
         //printf('<p class="btn btn-info">%s</p>', $wpisz_klase);
         //mysqli_query($polacz_school, $wpisz_numer);
      }
      if($licz_klasy[0]>1) {
         //wpisz numer do bazy
         printf('<p class="btn btn-danger">klasa wpisana kilka razy!</p>');
      }
/*
      // sprawdzenie czy znalezniony został nowy identyfikator ucznia - jeżeli nie - przejście do następnego rekordu
      if($stare_swiadectwo['uczen_nowy_ID'] == '')    {
         printf('<li>-------------- brak nowego id ucznia ---------------------------------------------------------</li>');
         continue;
      }
      
      /// TUTAJ poprawić - znaleźć datę wystwienia świadectwa (pobrać datę zakończenia roku szkolnego)
      $data_rady = $stare_swiadectwo['data_rady'];
      // wstawienie swiadectwa do nowej bazy danych
      $wstaw_swiadectwo = "INSERT INTO certificates(`student_id`, `type`, `council_date`, `date_of_issue`, `templates_id`) ";
      if( empty($stare_swiadectwo['data_rady']) )  $wstaw_swiadectwo.= "VALUES ('".$stare_swiadectwo['uczen_nowy_ID']."', 'świadectwo', NULL, NULL, '".$stare_swiadectwo['wzor_swiadectwa']."')";
      else     $wstaw_swiadectwo.= "VALUES ('".$stare_swiadectwo['uczen_nowy_ID']."', 'świadectwo', '".$data_rady."', NULL, '".$stare_swiadectwo['wzor_swiadectwa']."')";
      //printf('<li>%s</li>', $wstaw_swiadectwo);
      $wstaw = mysqli_query($polacz_school, $wstaw_swiadectwo);
      //$wstaw = 1;

      // usunięcie informacji o arkuszu ze starej bazy danych
      if($wstaw) {
         $usun_stare_swiadectwo = "UPDATE klasy_uczniowie SET wzor_swiadectwa=NULL WHERE id_ku = '".$stare_swiadectwo['id_ku']."'";
         printf('<li>%s</li>', $usun_stare_swiadectwo);
         mysqli_query($polacz_szkola, $usun_stare_swiadectwo);   
      }


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