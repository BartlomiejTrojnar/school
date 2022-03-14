<select name="textbook_id" class="form-control">
   <option value="0">- wybierz podręcznik -</option>
   @foreach($textbooks as $textbook)
      @if($textbook->id == $textbookSelected)
         <option selected="selected" value="{{$textbook->id}}">{{$textbook->title}}</option>
      @else
         <option value="{{$textbook->id}}">{{$textbook->title}}</option>
      @endif
   @endforeach
</select>