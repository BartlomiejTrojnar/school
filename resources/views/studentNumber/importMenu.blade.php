@extends('layouts.app')

@section('header')
   Import numerów uczniów
@endsection

@section('main-content')
   <h1>Import numerów uczniów</h1>

   <p style="margin-top: 50px;">Po naciśnięciu przycisku zostaną zaimportowane numery uczniów dla wybranej klasy z pliku <em>C:/dane/nauczyciele/numery_uczniow/importujNumery.xlsx</em></p>

   <form action="{{ route('numery_ucznia.import') }}" method="get" role="form">
      <p>Rok szkolny: <?php echo $schoolYearSF ?></p>
      <p>Klasa: {{ session()->get('gradeSelected') }} <?php echo $gradeSF ?></p>
      <p>Wybierz arkusz pliku: 
         <select name="sheet_name" id="">
            @foreach($sheets as $sheet)
               <option value="{{ $sheet->getTitle() }}">{{ $sheet->getTitle() }}</option>
            @endforeach
         </select>
      </p>

      <p style="width: 200px;">
         <input class="btn btn-primary" type="submit" value="importuj">
      </p>
   </form>
@endsection