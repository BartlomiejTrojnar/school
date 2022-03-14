@if( !empty( $links ) )
  {!! $subjects->render() !!}
@endif

<h2>{{ $subTitle }}</h2>
<table id="subjects">
  <thead>
    <tr>
      <th>lp</th>
      <?php
        echo view('layouts.thSorting', ["thName"=>"nazwa", "routeName"=>"przedmiot.orderBy", "field"=>"name", "sessionVariable"=>"SubjectOrderBy"]);
        echo view('layouts.thSorting', ["thName"=>"skrót", "routeName"=>"przedmiot.orderBy", "field"=>"short_name", "sessionVariable"=>"SubjectOrderBy"]);
        echo view('layouts.thSorting', ["thName"=>"aktualny?", "routeName"=>"przedmiot.orderBy", "field"=>"actual", "sessionVariable"=>"SubjectOrderBy"]);
        echo view('layouts.thSorting', ["thName"=>"kolejność w arkuszu", "routeName"=>"przedmiot.orderBy", "field"=>"order_in_the_sheet", "sessionVariable"=>"SubjectOrderBy"]);
        echo view('layouts.thSorting', ["thName"=>"rozszerzany?", "routeName"=>"przedmiot.orderBy", "field"=>"expanded", "sessionVariable"=>"SubjectOrderBy"]);
      ?>
      <th colspan="2">+/-</th>
    </tr>
  </thead>

  <tbody>
    <?php $count = 0; ?>
    @foreach($subjects as $subject)
      <?php $count++; ?>
      <tr>
        <td>{{ $count }}</td>
        <td><a href="{{ route('przedmiot.show', $subject->id) }}">{{ $subject->name }}</a></td>
        <td>{{ $subject->short_name }}</td>
        <td>
          @if( $subject->actual )
            <i class="fa fa-check"></i>
          @endif
        </td>
        <td>{{ $subject->order_in_the_sheet }}</td>
        <td>
          @if( $subject->expanded )
            <i class="fa fa-check"></i>
          @endif
        </td>

        <td class="edit"><a class="btn btn-primary" href="{{ route('przedmiot.edit', $subject->id) }}">
            <i class="fa fa-edit"></i>
        </a></td>
        <td class="destroy">
          <form action="{{ route('przedmiot.destroy', $subject->id) }}" method="post" id="delete-form-{{$subject->id}}">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}
            <button class="btn btn-primary"><i class="fa fa-remove"></i></button>
          </form>
        </td>
      </tr>
    @endforeach

    <tr class="create"><td colspan="8">
        <a class="btn btn-primary" href="{{ route('przedmiot.create') }}"><i class="fa fa-plus"></i></a>
    </td></tr>
  </tbody>
</table>