<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 07.01.2022 *********************** -->
@extends('layouts.app')

@section('java-script')
   <script src="{{ asset('public/js/group/grades.js') }}"></script>
@endsection

@section('header')
   <h1>Klasy należące do grupy</h1>
@endsection

@section('main-content')
   <form action="{{ route('grupa_klasy.store') }}" method="post" role="form">
      {{ csrf_field() }}
      <input type="hidden" name="group_id" value="{{ $group_id }}" />
      <table>
         <tr>
            <th><label for="grade_id">klasa</label></th>
            <td id="grades_list">
               <ul class="list-group" style="float: left;">
                  <?php $count=0; ?>
                  @foreach($grades as $grade)
                     <?php $count++; ?>
                     @if( $count > 1 && $grade->year_of_graduation != $grades[($count)-2]->year_of_graduation )
                        </ul>
                        <ul class="list-group" style="float: left;">
                     @endif
                     @if( array_search($grade->id, $gradesSelected) )
                        <li class="list-group-item list-group-item-dark active">
                           @if( $schoolYear )
                              <button data-checked="1" data-grade="{{$grade->id}}" class="btn btn-danger" data-year="{{$grade->year_of_graduation}}">
                                 {{ substr($schoolYear->date_end,0,4) - $grade->year_of_beginning}} {{$grade->symbol}}
                              <i class="fa fa-remove"></i></button>
                           @else
                              <button data-checked="1" data-grade="{{$grade->id}}" class="btn btn-danger" data-year="{{$grade->year_of_graduation}}">
                                 {{$grade->year_of_beginning}}-{{$grade->year_of_graduation}} {{$grade->symbol}}
                              <i class="fa fa-users"></i><i class="fa fa-remove"></i></button>
                           @endif
                        </li>
                     @else
                        <li class="list-group-item list-group-item-dark">
                           @if( $schoolYear )
                              @if($gradeSelectedYear==0 || $gradeSelectedYear == $grade->year_of_graduation)
                                 <button data-checked="0" data-grade="{{$grade->id}}" class="btn btn-info" data-year="{{$grade->year_of_graduation}}">
                                    {{ substr($schoolYear->date_end,0,4) - $grade->year_of_beginning }} {{$grade->symbol}}
                                 <i class="fa fa-users"></i></button>
                              @else
                                 <button data-checked="0" data-grade="{{$grade->id}}" class="btn btn-info disabled" data-year="{{$grade->year_of_graduation}}">
                                    {{ substr($schoolYear->date_end,0,4) - $grade->year_of_beginning }} {{$grade->symbol}}
                                 <i class="fa fa-users"></i></button>
                              @endif
                           @else
                             @if($gradeSelectedYear==0 || $gradeSelectedYear == $grade->year_of_graduation)
                                 <button data-checked="0" data-grade="{{$grade->id}}" class="btn btn-info" data-year="{{$grade->year_of_graduation}}">
                                    {{$grade->year_of_beginning}}-{{$grade->year_of_graduation}} {{$grade->symbol}}
                                 <i class="fa fa-users"></i><i class="fa fa-plus"></i></button>
                             @else
                                 <button data-checked="0" data-grade="{{$grade->id}}" class="btn btn-info disabled" data-year="{{$grade->year_of_graduation}}">
                                    {{$grade->year_of_beginning}}-{{$grade->year_of_graduation}} {{$grade->symbol}}
                                 <i class="fa fa-users"></i><i class="fa fa-plus"></i></button>
                             @endif
                           @endif
                        </li>
                     @endif
                  @endforeach
               </ul>
            </td>
         </tr>

         <tr class="submit"><td colspan="2">
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