<tr class="editRow" data-certificate_id="{{ $certificate->id }}">
<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 06.11.2021 *********************** -->
   <form action="{{ route('certificate.update', $certificate->id) }}" method="post" role="form">
   {{ csrf_field() }}
   {{ method_field('PATCH') }}

      <td><input type="hidden" name="student_id" value="{{ $certificate->student_id }}" /></td>
      <td><?php echo $typeSF; ?></td>
      <td><?php echo $templateSF; ?></td>
      <td><input type="date" name="council_date" value="{{ $certificate->council_date }}" /></td>
      <td><input type="date" name="date_of_issue" value="{{ $certificate->date_of_issue }}" /></td>

      <!-- komórka z przyciskami potwierdzenia zmiany i anulowania -->
      <td class="c" style="width: 250px;">
         <input type="hidden" name="id" value="{{ $certificate->id }}" />
         <button data-certificate_id="{{ $certificate->id }}" class="update btn btn-primary" data-version="{{ $version }}">zapisz zmiany</button>
         <button data-certificate_id="{{ $certificate->id }}" class="cancelUpdate btn btn-primary">anuluj</button>
      </td>
   </form>
</tr>