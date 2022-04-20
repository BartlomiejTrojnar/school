<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 07.01.2022 *********************** -->
@extends('layouts.app')

@section('main-content')

<h1>Przepisz plan lekcji ze starej bazy danych - skrypt w trakcie tworzenia</h1>
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
/*
   // wyszukanie ocen w starej bazie danych
   $wyszukaj_stara_grupe = "SELECT id_gu, gu.iducznia, u.newID as uczen_nowy_ID, u.imie, u.nazwisko, gu.idgrupy, g.newID as grupa_nowy_ID, gu.data_od, gu.data_do, p.nazwa ";
   $wyszukaj_stara_grupe.= "FROM grupy_uczniowie gu LEFT JOIN uczniowie u ON gu.iducznia=u.iducznia  LEFT JOIN grupy g ON gu.idgrupy=g.idgrupy ";
   $wyszukaj_stara_grupe.= "LEFT JOIN przedmioty p ON g.idprzedmiotu=p.idprzedmiotu";
   $wyszukaj_stara_grupa = mysqli_query($polacz_szkola, $wyszukaj_stara_grupe);
   echo '<ol>';
   while($ocena = mysqli_fetch_array($wyszukaj_stara_grupa)) {
      printf('<li><code style="background: yellow;">%d</code>: uczeń <a href="/school/uczen/%d">%s, %s</a> %d, (nowy ID: %d) :: w grupie %s od <code>%s</code> do <code>%s</code>, nowy id: <a href="/school/grupa/%d">%d</a>;</li>',
         $ocena['id_gu'], $ocena['uczen_nowy_ID'], $ocena['imie'], $ocena['nazwisko'], $ocena['iducznia'], $ocena['uczen_nowy_ID'], 
         $ocena['nazwa'], $ocena['data_od'], $ocena['data_do'], $ocena['grupa_nowy_ID'], $ocena['grupa_nowy_ID']);

      // znalezienie przedmiotu dla grupy w starej bazie danych
      $szukaj_nowy_przedmiot_ID = "SELECT p.newID FROM grupy g JOIN przedmioty p ON g.idprzedmiotu=p.idprzedmiotu WHERE idgrupy='".$ocena['idgrupy']."'";
      $zestaw_nowy_przedmiot_ID = mysqli_query($polacz_szkola, $szukaj_nowy_przedmiot_ID);
      $przedmiot = mysqli_fetch_array($zestaw_nowy_przedmiot_ID);
      //printf('<p>przedmiot: %s</p>', $przedmiot[0]);

      // znalezienie wpisu ucznia do grupy w nowej bazie danych
      $szukaj_nowa_grupa_ucznia = "SELECT gs.* FROM group_students gs LEFT JOIN groups g ON gs.group_id=g.id ";
      $szukaj_nowa_grupa_ucznia.= "WHERE g.subject_id='".$przedmiot['newID']."' AND student_id='".$ocena['uczen_nowy_ID']."'";
      $zestaw_nowa_grupa_ucznia = mysqli_query($polacz_school, $szukaj_nowa_grupa_ucznia);
      //printf('<p>zapytanie nowa grupa: %s</p>', $szukaj_nowa_grupa_ucznia);
      $licz_nowa_grupa_ucznia = $zestaw_nowa_grupa_ucznia -> num_rows;
      //printf('<p>liczba grup: %s</p>', $licz_nowa_grupa_ucznia);

      if($licz_nowa_grupa_ucznia == 0) {
         printf('<li><span class="btn btn-warning">Brak grup ucznia - wpisz ucznia do grupy !!!.</span><br />');
         printf('<code style="background: yellow;">%d</code>: uczeń <a href="/school/uczen/%d">%s, %s</a> %d, (nowy ID: %d) w grupie %s od <code>%s</code> do <code>%s</code>, nowy id: <a href="/school/grupa/%d">%d</a>;<br />',
            $ocena['id_gu'], $ocena['uczen_nowy_ID'], $ocena['imie'], $ocena['nazwisko'], $ocena['iducznia'], $ocena['uczen_nowy_ID'], 
            $ocena['nazwa'], $ocena['data_od'], $ocena['data_do'], $ocena['grupa_nowy_ID'], $ocena['grupa_nowy_ID']);
         printf('</li>');
         /*         
         $wstaw_ucznia_do_grupy = "INSERT INTO group_students(group_id, student_id, start, end) ";
         $wstaw_ucznia_do_grupy.= "VALUES ('".$ocena['grupa_nowy_ID']."', '".$ocena['uczen_nowy_ID']."', '".$ocena['data_od']."', '".$ocena['data_do']."')";
         printf('<p>%s</p>', $wstaw_ucznia_do_grupy);
         //mysqli_query($polacz_school, $wstaw_ucznia_do_grupy);
         //$licz_nowa_grupa_ucznia = 1;
         
      }

      if($licz_nowa_grupa_ucznia == 1) {
         $nowa_grupa_ucznia = mysqli_fetch_array($zestaw_nowa_grupa_ucznia);
         if($nowa_grupa_ucznia['start'] <= $ocena['data_od'] && $nowa_grupa_ucznia['end']>=$ocena['data_do']) {
            $kasuj_stara_grupe = "DELETE FROM grupy_uczniowie WHERE id_gu='".$ocena['id_gu']."'";
            //printf('<p>%s</p>', $kasuj_stara_grupe);
            mysqli_query($polacz_szkola, $kasuj_stara_grupe);
         }
         else {
            printf('<p>Daty się nie zgadzają, w nowej grupie od %s do %s</p>', $nowa_grupa_ucznia['start'], $nowa_grupa_ucznia['end']);
         }
      }

      if($licz_nowa_grupa_ucznia > 1) {
         printf('<li><span class="btn btn-danger">Więcej grup dla ucznia - sprawdź to!.</span><br />');
         printf('<code style="background: yellow;">%d</code>: uczeń <a href="/school/uczen/%d">%s, %s</a> %d, (nowy ID: %d) :: w grupie %s od <code>%s</code> do <code>%s</code>, nowy id: <a href="/school/grupa/%d">%d</a>;<br />',
            $ocena['id_gu'], $ocena['uczen_nowy_ID'], $ocena['imie'], $ocena['nazwisko'], $ocena['iducznia'], $ocena['uczen_nowy_ID'], 
            $ocena['nazwa'], $ocena['data_od'], $ocena['data_do'], $ocena['grupa_nowy_ID'], $ocena['grupa_nowy_ID']);
         printf('<span>Znaleziono %s grup.</span><br />', $licz_nowa_grupa_ucznia);
         printf('<ol type="a">');
         $licz_grupy=0;
         while( $nowa_grupa_ucznia = mysqli_fetch_array($zestaw_nowa_grupa_ucznia) ) {
            printf('<li>Grupa %d - od %s do %s</li>', $nowa_grupa_ucznia['group_id'], $nowa_grupa_ucznia['start'], $nowa_grupa_ucznia['end']);
            if( $nowa_grupa_ucznia['start']<=$ocena['data_od'] && $nowa_grupa_ucznia['end']>=$ocena['data_do'] ) {
               $licz_grupy++;
            }
         }
         printf('</ol>liczba grup dla dat: %s.</li>', $licz_grupy);
         if($licz_grupy==1) {
            $kasuj_stara_grupe = "DELETE FROM grupy_uczniowie WHERE id_gu='".$ocena['id_gu']."'";
            //printf('<p class="btn btn-info">%s ---- liczba grup %s</p>', $kasuj_stara_grupe, $licz_grupy);
            mysqli_query($polacz_szkola, $kasuj_stara_grupe);
         }
         else {
            $szukaj_nowa_grupa_ucznia = "SELECT gs.* FROM group_students gs WHERE group_id='".$ocena['grupa_nowy_ID']."' AND student_id='".$ocena['uczen_nowy_ID']."'";
            $zestaw_nowa_grupa_ucznia = mysqli_query($polacz_school, $szukaj_nowa_grupa_ucznia);
            $licz_nowa_grupa_ucznia = $zestaw_nowa_grupa_ucznia -> num_rows;
            if($licz_nowa_grupa_ucznia==1)   {
               printf('<p class="btn btn-warning">Kasuj stary wpis.</p>');
               $kasuj_stara_grupe = "DELETE FROM grupy_uczniowie WHERE id_gu='".$ocena['id_gu']."'";
               mysqli_query($polacz_szkola, $kasuj_stara_grupe);   
            }
            else  printf('<p><span class="btn btn-info">Więcej niż 1 potencjalna grupa:</span> %s</p>', $szukaj_nowa_grupa_ucznia);
         }
      }
   };
echo '</ol>';
?>

@endsection