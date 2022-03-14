<section id="studentNumbers">
<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 05.01.2022 *********************** -->
   <h3>numery w dziennikach</h3>
   <table>
      <tr>
         <?php
            echo view('layouts.thSorting', ["thName"=>"klasa", "routeName"=>"numery_ucznia.orderBy", "field"=>"grade_id", "sessionVariable"=>"StudentNumberOrderBy"]);
            echo view('layouts.thSorting', ["thName"=>"rok szkolny", "routeName"=>"numery_ucznia.orderBy", "field"=>"school_year_id", "sessionVariable"=>"StudentNumberOrderBy"]);
            echo view('layouts.thSorting', ["thName"=>"numer", "routeName"=>"numery_ucznia.orderBy", "field"=>"number", "sessionVariable"=>"StudentNumberOrderBy"]);
         ?>
         <th>popraw / usuń</th>
      </tr>

      @foreach($studentNumbers as $sn)
         <tr class="c number_row confirmation{{$sn->confirmation_number}}" data-student_number_id="{{ $sn->id }}" data-school_year_id="{{ $sn->school_year_id }}" >
            <td>
               @if( $yearOfStudy )
                  {{ $yearOfStudy - $sn->grade->year_of_beginning }}{{ $sn->grade->symbol }}
               @else
                  {{ $sn->grade->year_of_beginning }}-{{ $sn->grade->year_of_graduation }}{{ $sn->grade->symbol }}
               @endif
            </td>
            <td>{{ substr($sn->school_year->date_start, 0, 4) }}/{{ substr($sn->school_year->date_end, 0, 4) }}</td>

            @if($sn->confirmation_number==1)
               <td>
            @else
               <td class="not_confirmation">
            @endif
               <span class="number" data-id="{{ $sn->id }}" data-school_year_id="{{ $sn->school_year_id }}">{{ $sn->number }}</span>
            </td>

            <td class="destroy edit">
               <button class="edit btn btn-primary"    data-student_number_id="{{ $sn->id }}"><i class="fa fa-edit"></i></button>
               <button class="destroy btn btn-primary" data-student_number_id="{{ $sn->id }}"><i class="fas fa-remove"></i></button>
            </td>
         </tr>
      @endforeach

      <tr class="create"><td colspan="4">
         <button class="showCreateRow btn btn-primary"><i class="fa fa-plus"></i></button>
      </td></tr>
   </table>
</section>