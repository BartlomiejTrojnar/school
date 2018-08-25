<select name="group_id">
  <option value="0">- wybierz grupę -</option>
  @foreach($groups as $group)
    @if($group->id == $selectedGroup)
      <option selected="selected" value="{{$group->id}}">{{$group->subject->name}} {{$group->date_start}}</option>
    @else
      <option value="{{$group->id}}">{{$group->subject->name}} {{$group->date_start}}</option>
    @endif
  @endforeach
</select>