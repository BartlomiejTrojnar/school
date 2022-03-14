<!-- **********************  (C) mgr inż. Bartłomiej Trojnar; 22.02.2022 ********************** -->
@section('css')
   <link href="{{ asset('public/css/lessonPlan.css') }}" rel="stylesheet">
@endsection

<section id="lessonPlanHeader" style="text-align: center;">
   <strong>plan lekcji dla daty:
      <input type="date" id="dateView" value="{{ $dateView }}" />
   </strong>
</section>

<table id="studentPlan">
   <thead>
      <tr>
         <th>godziny</th>
         <th>poniedziałek</th>
         <th>wtorek</th>
         <th>środa</th>
         <th>czwartek</th>
         <th>piątek</th>
      </tr>
   </thead>

   <tbody>
   @for($i=1; $i<=10; $i++)
      <tr>
         <th>{{ $i }}</th>
         @for($dzien=0; $dzien<=60; $dzien=$dzien+15)
            <td class="c" data-lessonHour_id="{{$i+$dzien}}">
            @foreach($lessons as $lesson)
               @if($lesson->lesson_hour_id == $i+$dzien)
                  <div class="lesson" data-start="{{$lesson->start}}" data-end="{{$lesson->end}}">
                     <span class="lessonDates"><span class="start">{{$lesson->start}}</span>-<span class="end">{{$lesson->end}}</span></span><br />
                     {{ $lesson->group->subject->name }}
                     <em style="font-size: 0.8em;">( {{ $lesson->group->level }} {{ $lesson->group->comments }} )</em>
                     @if($lesson->classroom_id) {{ $lesson->classroom->name }} @endif
                     @foreach($lesson->group->teachers as $groupTeacher)
                        <span class="teacher" data-start="{{$groupTeacher->start}}" data-end="{{$groupTeacher->end}}">/{{ $groupTeacher->teacher->first_name }} {{ $groupTeacher->teacher->last_name }}/</span>
                     @endforeach
                  </div>
               @endif
            @endforeach
            </td>
         @endfor
      </tr>
   @endfor
   </tbody>
</table>