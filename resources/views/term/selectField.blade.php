<select name="term_id">
   <option value="0">- wybierz termin -</option>
   @foreach($terms as $term)
      @if($term->id == $termSelected)
         <option selected="selected" value="{{$term->id}}">{{$term->exam_description_id}} {{$term->classroom->name}} {{$term->date_start}}</option>
      @else
         <option value="{{$term->id}}">{{$term->exam_description_id}} {{$term->classroom->name}} {{$term->date_start}}</option>
      @endif
   @endforeach
</select>