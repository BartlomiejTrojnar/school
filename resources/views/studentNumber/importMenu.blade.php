@extends('layouts.app')

@section('header')
   Import numerów uczniów
@endsection

@section('main-content')
   <h1>Import numerów uczniów</h1>
   <p>Po naciśnięciu przycisku zostaną zaimportowane numery uczniów dla wybranej klasy z pliku <em>C:/dane/nauczyciele/numery_uczniow/importujNumery.xlsx</em></p>
   <p>Klasa: {{ session()->get('gradeSelected') }}</p>
   <p>Rok szkolny: {{ session()->get('schoolYearSelected') }}</p>
   <a href="importuj">importuj</a>
@endsection