<tr data-certificate_id="{{ $certificate->id }}">
<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 06.11.2022 *********************** -->
   <td>{{ $lp }}</td>
   <td>{{ $certificate->type }}</td>
   <td>{{ $certificate->templates_id }}</td>
   <td>{{ $certificate->council_date }}</td>
   <td>{{ $certificate->date_of_issue }}</td>

   <!-- modyfikowanie i usuwanie -->
   <td class="destroy edit c">
      <button data-certificate_id="{{ $certificate->id }}" class="edit btn btn-primary" data-version="{{$version}}"><i class="fa fa-edit"></i></button>
      <button data-certificate_id="{{ $certificate->id }}" class="destroy btn btn-primary"><i class="fas fa-remove"></i></button>
   </td>
</tr>