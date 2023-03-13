<!-- **********************  (C) mgr inż. Bartłomiej Trojnar; 31.12.2022 ********************** -->
@if( !empty( $links ) )
   {!! $textbooks->render() !!}
@endif

<h2>{{ $subTitle }}</h2>
<table id="textbooks">
   <thead>
      <tr>
         <th>id</th>
         <?php
            echo view('layouts.thSorting', ["thName"=>"przedmiot", "routeName"=>"podrecznik.orderBy", "field"=>"subject_id", "sessionVariable"=>"TextbookOrderBy"]);
            echo view('layouts.thSorting', ["thName"=>"autor", "routeName"=>"podrecznik.orderBy", "field"=>"author", "sessionVariable"=>"TextbookOrderBy"]);
            echo view('layouts.thSorting', ["thName"=>"tytuł", "routeName"=>"podrecznik.orderBy", "field"=>"title", "sessionVariable"=>"TextbookOrderBy"]);
            echo view('layouts.thSorting', ["thName"=>"wydawnictwo", "routeName"=>"podrecznik.orderBy", "field"=>"publishing_house", "sessionVariable"=>"TextbookOrderBy"]);
            echo view('layouts.thSorting', ["thName"=>"dopuszczenie", "routeName"=>"podrecznik.orderBy", "field"=>"admission", "sessionVariable"=>"TextbookOrderBy"]);
         ?>
         <th>uwagi</th>
         <th>ilość<br>wyborów</th>
         <th>aktualizacja</th>
         <th>zmień/usuń</th>
      </tr>

      <tr>
         <td></td>
         <td><?php  print_r($subjectSF);  ?></td>
         <td colspan="8"></td>
      </tr>
   </thead>

   <tbody>
      <?php $count = 0; ?>
      @foreach($textbooks as $textbook)
         <?php $count++; ?>
         <tr data-textbook_id="{{$textbook->id}}">
            <td>{{ $count }}</td>
            <td><a href="{{ route('przedmiot.show', $textbook->subject_id) }}">{{ $textbook->subject->name }}</a></td>
            <td>{{ $textbook->author }}</td>
            <td><a href="{{ route('podrecznik.show', $textbook->id) }}">{{ $textbook->title }}</a></td>
            <td>{{ $textbook->publishing_house }}</td>
            <td>{{ $textbook->admission }}</td>
            <td>{{ $textbook->comments }}</td>
            <td class="c">{{ count($textbook->textbookChoices) }}</td>
            <td>{{ substr($textbook->updated_at, 0, 10) }}</td>
            <td class="edit destroy c">
               <button class="edit btn btn-primary"    data-textbook_id="{{ $textbook->id }}"><i class="fa fa-edit"></i></button>
               <button class="destroy btn btn-primary" data-textbook_id="{{ $textbook->id }}"><i class="fas fa-remove"></i></button>
            </td>
         </tr>
      @endforeach

      <tr class="create"><td colspan="10">
         <button id="showCreateRow" class="btn btn-primary"><i class="fa fa-plus"></i></button>
         <var id="countTextbooks" hidden>{{$count}}</var>
      </td></tr>
   </tbody>
</table>