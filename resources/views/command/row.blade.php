<tr data-command_id="{{ $command->id }}">
<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 07.02.2023 *********************** -->
   <td>{{ $lp }}</td>
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