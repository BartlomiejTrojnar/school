<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 03.11.2021 *********************** -->
<tr data-certificate_id="{{ $certificate->id }}">
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