<table>
<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 16.06.2023 *********************** -->
   <tr>
      <th>lp</th>
      <?php
         echo view('layouts.thSorting', ["thName"=>"uczeń", "routeName"=>"numery_ucznia.orderBy", "field"=>"last_name", "sessionVariable"=>"StudentNumberOrderBy"]);
         echo view('layouts.thSorting', ["thName"=>"rok szkolny", "routeName"=>"numery_ucznia.orderBy", "field"=>"school_year_id", "sessionVariable"=>"StudentNumberOrderBy"]);
         echo view('layouts.thSorting', ["thName"=>"numer", "routeName"=>"numery_ucznia.orderBy", "field"=>"number", "sessionVariable"=>"StudentNumberOrderBy"]);
      ?>
      <th>zmień / usuń</th>
   </tr>

   <tr>
      <td colspan="2"></td>
      <td><?php  print_r($schoolYearSF);  ?></td>
      <td colspan="2"></td>
   </tr>

   <?php $count = 0; ?>
   @foreach($studentNumbers as $sn)
      <tr class="number_row confirmation{{ $sn->confirmation_number }}" data-student_number_id="{{ $sn->id }}" data-school_year_id="{{ $sn->school_year_id }}">
         <td class="lp">{{ ++$count }}</td>
         <td>
            <?php
            $del = false;
            foreach($sn->student->grades as $sg) if($sg->grade_id == $grade->id) {
               if( substr($sn->school_year->date_of_graduation,0,4)==$grade->year_of_graduation )
                  $grade_end = $sn->school_year->date_of_graduation_of_the_last_grade;
               else $grade_end = $sn->school_year->date_of_graduation;
               if($sg->end < $grade_end || $sg->end < $grade_end)    $del = true;
            }
            ?>
            <a href="{{ route('uczen.show', $sn->student_id) }}">
               @if($del) <del style="color: #66b;"> @endif
               {{ $sn->student->first_name }} {{ $sn->student->second_name }} {{ $sn->student->last_name }}
               @if($del) </del> @endif
            </a>
         </td>
         <td class="c">{{ substr($sn->school_year->date_start, 0, 4) }}/{{ substr($sn->school_year->date_end, 0, 4) }}</td>
         @if($sn->confirmation_number==1)
            <td class="c">
         @else
            <td class="c not_confirmation">
         @endif
            <span class="number" data-id="{{ $sn->id }}" data-school_year_id="{{ $sn->school_year_id }}">{{ $sn->number }}</span>
         </td>
         <td class="destroy edit c">
            <button class="edit btn btn-primary"    data-student_number_id="{{ $sn->id }}"><i class="fa fa-edit"></i></button>
            <button class="destroy btn btn-primary" data-student_number_id="{{ $sn->id }}"><i class="fa fa-remove"></i></button>
         </td>
      </tr>
   @endforeach

   <tr class="create"><td colspan="5">
      <button id="showCreateRow" class="btn btn-primary"><i class="fa fa-plus"></i></button>
   </td></tr>
</table>
<script src="http://code.jquery.com/color/jquery.color-2.1.2.js"></script>