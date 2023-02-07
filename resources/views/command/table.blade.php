<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 08.12.2021 *********************** -->
@section('java-script')
   <script language="javascript" type="module" src="{{ url('public/js/command/forTask.js') }}"></script>
@endsection

<h2>polecenia</h2>

<table id="commands">
   <thead>
      <tr>
         <th>lp</th>
         <?php
            echo view('layouts.thSorting', ["thName"=>"numer", "routeName"=>"polecenie.orderBy", "field"=>"number", "sessionVariable"=>"CommandOrderBy"]);
            echo view('layouts.thSorting', ["thName"=>"polecenie", "routeName"=>"polecenie.orderBy", "field"=>"command", "sessionVariable"=>"CommandOrderBy"]);
            echo view('layouts.thSorting', ["thName"=>"opis", "routeName"=>"polecenie.orderBy", "field"=>"description", "sessionVariable"=>"CommandOrderBy"]);
            echo view('layouts.thSorting', ["thName"=>"punkty", "routeName"=>"polecenie.orderBy", "field"=>"points", "sessionVariable"=>"CommandOrderBy"]);
         ?>
         <th>wprowadzono</th>
         <th>aktualizacja</th>
         <th>zmień / usuń</th>
      </tr>
   </thead>

   <tbody>
      @if( !empty($commands) )
         <?php $count=0; ?>
         @foreach($commands as $command)
            <tr data-command_id="{{$command->id}}">
               <td>{{ ++$count }}</td>
               <td class="c">{{ $command->number }}</td>
               <td><a href="{{ route('polecenie.show', $command->id) }}">{{ $command->command }}</a></td>
               <td>{{ $command->description }}</td>
               <td class="c">{{ $command->points }}</td>
               <td class="c small">{{ substr($command->created_at, 0, 10) }}</td>
               <td class="c small">{{ substr($command->updated_at, 0, 10) }}</td>
               <td class="edit destroy c">
                  <button class="edit btn btn-primary"      data-command_id="{{ $command->id }}"><i class="fa fa-edit"></i></button>
                  <button class="destroy btn btn-primary"   data-command_id="{{ $command->id }}"><i class="fas fa-remove"></i></button>
               </td>
            </tr>
         @endforeach
      @endif

      <tr class="create"><td colspan="9">
         <button id="showCreateRow" class="btn btn-primary"><i class="fa fa-plus"></i></button>
      </td></tr>
   </tbody>
</table>
<input type="hidden" id="countCommands" value="{{ count($commands) }}" />

<a class="btn btn-primary" href="{{ route('polecenie.taskCommandExport', $task->id) }}">eksportuj polecenia<span class="glyphicon glyphicon-export"></span></a>
&lt; plik Excel &gt;
<a class="btn btn-primary" href="{{ route('polecenie.taskCommandImport', $task->id) }}">importuj polecenia<span class="glyphicon glyphicon-import"></span></a>