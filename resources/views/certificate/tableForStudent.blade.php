@section('title')
   <title>świadectwa - {{ $student->first_name }} {{ $student->last_name }}</title>
@endsection

<section id="certificatesSection">
<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 25.10.2022 *********************** -->
   <h2>Świadectwa ucznia</h2>
   <table id="certificatesTable">
      <thead>
         <tr>
            <th>id</th>
            <th>typ</th>
            <th>wzór</th>
            <th>data Rady Pedag.</th>
            <th>data wystawienia</th>
            <th>zmień / usuń</th>
         </tr>
      </thead>

      <tbody>
         <?php $count=0; ?>
         @foreach($certificates as $certificate)
            <tr data-certificate_id="{{ $certificate->id }}">
               <td>{{ ++$count }}</td>
               <td>{{ $certificate->type }}</td>
               <td>{{ $certificate->templates_id }}</td>
               <td>{{ $certificate->council_date }}</td>
               <td>{{ $certificate->date_of_issue }}</td>

               <!-- modyfikowanie i usuwanie -->
               <td class="destroy edit c">
                  <button class="edit btn btn-primary"    data-certificate_id="{{ $certificate->id }}" data-version="forStudent"><i class="fa fa-edit"></i></button>
                  <button class="destroy btn btn-primary" data-certificate_id="{{ $certificate->id }}"><i class="fas fa-remove"></i></button>
               </td>
            </tr>
         @endforeach

         <tr class="create footer"><td colspan="6">
            <button id="showCreateRow" class="btn btn-primary" data-version="forStudent"><i class="fa fa-plus"></i></button>
            <input type="hidden" name="lp" value="{{$count+1}}" />
         </td></tr>
      </tbody>
   </table>
</section>