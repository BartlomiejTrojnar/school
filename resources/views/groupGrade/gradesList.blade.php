<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 28.06.2023 *********************** -->
@extends('layouts.app')

@section('java-script')
   <script src="{{ asset('public/js/groupGrade/forGroup.js') }}"></script>
@endsection

@section('css')
   <link href="{{ asset('public/css/'.$css) }}" rel="stylesheet">
@endsection

@section('header')
   <h1>Klasy należące do grupy</h1>
@endsection

@section('main-content')
   <form action="{{ route('grupa_klasy.store') }}" method="post" role="form">
      {{ csrf_field() }}
      <input type="hidden" name="group_id" value="{{ $group_id }}" />
      <table id="gradesTable">
         <tr>
            <th>klasy 1</th>
            <th>klasy 2</th>
            <th>klasy 3</th>
            <th>klasy 4</th>
            <th>klasy LOD</th>
         </tr>

         <tr>
            {{ createTD(1, $year-$gradeSelectedYear, $year, $grades, $gradesSelected, $group_id) }}
            {{ createTD(2, $year-$gradeSelectedYear, $year, $grades, $gradesSelected, $group_id) }}
            {{ createTD(3, $year-$gradeSelectedYear, $year, $grades, $gradesSelected, $group_id) }}
            {{ createTD(4, $year-$gradeSelectedYear, $year, $grades, $gradesSelected, $group_id) }}

            <td id="grades_list_5"><ul class="list-group">
               @foreach($grades as $grade)
                  @if( $grade->school_id!=1 )
                     @if( array_search($grade->id, $gradesSelected) )
                        {{ createLi($grade, $year, "errorChecked") }}
                     @else
                        {{ createLi($grade, $year, "disabled") }}
                     @endif
                  @endif   <!-- koniec sprawdzenia czy szkoła to II LO -->
               @endforeach
            </ul></td>
         </tr>

         <tr class="submit"><td colspan="5">
            @if( $version=="forIndex" )
               <a class="btn btn-primary" href="{{ route('grupa.index') }}">zapisz</a>
            @endif
            @if( $version=="forGroup" )
               <a class="btn btn-primary" href="{{ route('grupa.show', session()->get('groupSelected'), 'showInfo') }}">zapisz</a>
            @endif
            @if( $version=="forTeacher" )
               <a class="btn btn-primary" href="{{ route('nauczyciel.show', session()->get('teacherSelected'), 'showGroups') }}">zapisz</a>
            @endif
         </td></tr>
      </table>
   </form>
@endsection

<?php
   function createLi($grade, $year, $type, $group=0) {
      ?>
      <li class="list-group-item">
         <button class="{{ $type }}" data-grade="{{ $grade->id }}" data-year="{{ $grade->year_of_beginning }}">
            {{ $year - $grade->year_of_beginning }}{{ $grade->symbol }}
            @if( $type=="abled" )
               <i class="fa fa-plus"></i>
            @elseif( $type=="disabled" )
               <i class="fa fa-lock"></i>
            @else
               <i class="fa fa-remove"></i>
            @endif
         </button>
         @if($type!="disabled")
            @foreach($grade->groups as $gg)
               @if($gg->group_id == $group)
                  <input type="text" name="name{{ $grade->id }}" placeholder="nazwa grupy w klasie" size="18" value="{{ $gg->name }}">
               @endif
            @endforeach
         @endif
      </li>
      <?php
   }

   function createTD($yearOfStudy, $yearSelected, $year, $grades, $gradesSelected, $group_id) {
      if($yearOfStudy==$yearSelected) {   $type1 = "checked";  $type2 = "abled"; }
      else {   $type1 = "errorChecked";   $type2 = "disabled"; }
      ?>
      <td id="grades_list_{{ $yearOfStudy }}"><ul class="list-group">
         @foreach($grades as $grade)
            @if( $grade->school_id==1 && $grade->year_of_beginning==$year-$yearOfStudy )
               @if( array_search($grade->id, $gradesSelected) )
                  {{ createLi($grade, $year, $type1, $group_id) }}
               @else
                  {{ createLi($grade, $year, $type2, $group_id) }}
               @endif   <!-- koniec sprawdzenia czy klasa jest zaznaczona (wybrana do grupy) -->
            @endif   <!-- koniec sprawdzenia czy szkoła to II LO -->
         @endforeach
      </ul></td>
      <?php
   }
?>