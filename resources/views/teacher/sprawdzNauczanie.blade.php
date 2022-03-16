<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 07.01.2022 *********************** -->
@extends('layouts.app')

@section('main-content')
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
   $wyszukaj_stare_nauczania = "SELECT idnauczania, np.idnauczyciela, np.idprzedmiotu, p.newID as przedmiot_nowy_ID, n.imie, n.nazwisko, p.nazwa FROM nauczane_przedmioty np LEFT JOIN przedmioty p ON np.idprzedmiotu=p.idprzedmiotu LEFT JOIN nauczyciele n ON np.idnauczyciela=n.idnauczyciela";
   $zestaw_stare_nauczania = mysqli_query($polacz_szkola, $wyszukaj_stare_nauczania);
   echo '<ol>';
	while($stare_nauczanie = mysqli_fetch_array($zestaw_stare_nauczania)) {
      printf('<p>%s %s id %d -> przedmiot %s id: %d, przedmiot_nowy_ID: %d</p>', $stare_nauczanie['imie'], $stare_nauczanie['nazwisko'], $stare_nauczanie['idnauczyciela'], $stare_nauczanie['nazwa'], $stare_nauczanie['idprzedmiotu'], $stare_nauczanie['przedmiot_nowy_ID']);

      // wyszukanie id nauczyciela w nowej bazie danych
      $wyszukaj_id_nauczyciela = "SELECT id FROM teachers WHERE last_name='".$stare_nauczanie['nazwisko']."' AND first_name='".$stare_nauczanie['imie']."'";
      $zestaw_id_nauczyciela = mysqli_query($polacz_school, $wyszukaj_id_nauczyciela);
      $id_nauczyciela = mysqli_fetch_array($zestaw_id_nauczyciela);

      // sprawdzenie czy w nowej bazie danych jest wpis dotyczący nauczania przedmiotu (1 raz)
      $wyszukaj_nowe_nauczanie = "SELECT id FROM taught_subjects WHERE teacher_id='".$id_nauczyciela[0]."' AND subject_id='".$stare_nauczanie['przedmiot_nowy_ID']."'";
      $zestaw_nowe_nauczanie = mysqli_query($polacz_school, $wyszukaj_nowe_nauczanie);
      $liczba_wpisow = mysqli_num_rows($zestaw_nowe_nauczanie);
      if($liczba_wpisow==0) {
         printf('<p class="btn btn-info">Brak nauczania</p>');
      }
      if($liczba_wpisow>1) {
         printf('<p class="btn btn-danger">Wpis wiele razy nauczania</p>');
      }
      if($liczba_wpisow==1) {
         $kasuj_stare_nauczania = "DELETE FROM nauczane_przedmioty WHERE idnauczania='".$stare_nauczanie['idnauczania']."'";
         if( mysqli_query($polacz_szkola, $kasuj_stare_nauczania) )
         printf('<p class="btn-warning">Jedno nauczanie - kasujemy :) %s</p>', $kasuj_stare_nauczania);
      }

/*
     
      if($liczba_wpisow[0]==1) {
         $query_delete = "DELETE FROM grupy_nauczyciele WHERE idgrupy='".$row['idgrupy']."' AND idnauczyciela='".$row['idnauczyciela']."' AND data_od='".$row['data_od']."' AND data_do='".$row['data_do']."'";
      //   printf('<li>%s <a href="/school/grupa/%s/showInfo">%d</a></li>', $query_delete, $row['new_grupa'], $row['new_grupa']);
         mysqli_query($polacz_szkola, $query_delete);
      }
      if($liczba_wpisow[0]==0) {
         $query_delete = "** DELETE FROM grupy_nauczyciele WHERE idgrupy='".$row['idgrupy']."' AND idnauczyciela='".$row['idnauczyciela']."' AND data_od='".$row['data_od']."' AND data_do='".$row['data_do']."' **";
         printf('<li>%s <a href="/school/grupa/%s/showInfo">%d</a></li>', $query_delete, $row['new_grupa'], $row['new_grupa']);
         // znajdz date ukończenia grupy
         $query_grupa_end = "SELECT end FROM groups WHERE id='".$row['new_grupa']."'";
         $result_grupa_end = mysqli_query($polacz_school, $query_grupa_end);
         $end = mysqli_fetch_array($result_grupa_end);
         printf('<li>data końcowa grupy: %s</li>', $end['end']);

         // znajdz date koncowa dla nauczyciela (jeżeli jest 1)
         $query_n_end = "SELECT max(end) as end FROM group_teachers WHERE group_id='".$row['new_grupa']."'";
         $result_n_end = mysqli_query($polacz_school, $query_n_end);
         $ile_n_end = mysqli_num_rows($result_n_end);
         if($ile_n_end==1) {
            $row_n_end = mysqli_fetch_array($result_n_end);
            printf('<li>data końcowa nauczyciela: %s</li>', $row_n_end['end']);
            $query_g_end= "UPDATE groups SET end='".$row_n_end['end']."' WHERE id='".$row['new_grupa']."'";
            printf('<li>%s</li>', $query_g_end);
            mysqli_query($polacz_school, $query_g_end);
            $query_delete = "DELETE FROM grupy_nauczyciele WHERE idgrupy='".$row['idgrupy']."' AND idnauczyciela='".$row['idnauczyciela']."' AND data_od='".$row['data_od']."' AND data_do='".$row['data_do']."'";
            mysqli_query($polacz_szkola, $query_delete);
         }


      }

      if($row['data_do']=='2020-06-26') {
         $stara_data_koncowa = '2020-08-31';
         // przedłużenie czasu nauczania przez nauczycieli dla grupy (tych, którzy uczyli do dotychczasowej daty końcowej grupy)
         $query_update = "UPDATE group_teachers SET end='".$row['data_do']."' WHERE end='".$stara_data_koncowa."' AND group_id='".$row['new_grupa']."'";
         //printf('<li>%s</li>', $query_update);
         mysqli_query($polacz_school, $query_update);

         // przedłużenie czasu przynależności uczniów do grupy (tych, którzy należeli do dotychczasowej daty końcowej grupy)
         $query_update = "UPDATE group_students SET end='".$row['data_do']."' WHERE end='".$stara_data_koncowa."' AND group_id='".$row['new_grupa']."'";
         //printf('<li>%s</li>', $query_update);
         mysqli_query($polacz_school, $query_update);

         // zmaina daty końcowej dla grupy
         $query_update = "UPDATE groups SET end='".$row['data_do']."' WHERE id='".$row['new_grupa']."'";
         //printf('<li>%s <a href="/school/grupa/%s/showInfo">%d</a></li>', $query_update, $row['new_grupa'], $row['new_grupa']);
         mysqli_query($polacz_school, $query_update);
      }
*/

   };
   echo '</ol>';
?>

@endsection