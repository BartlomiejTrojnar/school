<!-- ********************** (C) mgr inż. Bartłomiej Trojnar; 06.11.2021 *********************** -->
<section id="declarationsTable">
   {!! $declarations->render() !!}
   @if( !count($declarations) && (empty($_GET['page']) || $_GET['page']>1) )
     <ul class="pagination">
       <li><a id="jumpToThePage" href="{{route('deklaracja.index', 'page=1')}}">1</a></li>
     </ul>
   @endif

   <h2>Deklaracje w sesji</h2>
   <div><a class="btn btn-primary" href="{{route('deklaracja.create', 'version=forGrade')}}">dodaj deklaracje dla klasy</a></div>

   <table id="declarations">
      <thead>
         <tr>
            <th>id</th>
            <?php
               echo view('layouts.thSorting', ["thName"=>"uczeń", "routeName"=>"deklaracja.orderBy", "field"=>"last_name", "sessionVariable"=>"DeclarationOrderBy"]);
               echo view('layouts.thSorting', ["thName"=>"numer zgłoszenia", "routeName"=>"deklaracja.orderBy", "field"=>"application_number", "sessionVariable"=>"DeclarationOrderBy"]);
               echo view('layouts.thSorting', ["thName"=>"kod ucznia", "routeName"=>"deklaracja.orderBy", "field"=>"student_code", "sessionVariable"=>"DeclarationOrderBy"]);
            ?>
            <th>egzaminy</th>
            <th>wprowadzono</th>
            <th>aktualizacja</th>
            <th>operacje</th>
         </tr>

         <tr>
            <td></td>
            <td><?php print_r($gradeSelectField); ?></td>
            <td colspan="6"></td>
         </tr>
      </thead>

      <tbody>
         <?php $count=0;  if(isset($_GET['page'])) $page = $_GET['page']; else $page=1; ?>
         @foreach($declarations as $declaration)
            <tr data-declaration_id="{{$declaration->id}}">
               <td><a href="{{ route('deklaracja.show', $declaration->id) }}">{{$page*25-25+ ++$count}}</a></td>
               <td><a href="{{ route('uczen.show', $declaration->student_id) }}">{{ $declaration->student->first_name }} {{ $declaration->student->last_name }}</a></td>
               <td class="c">{{ $declaration->application_number }}</td>
               <td>{{ $declaration->student_code }}</td>
               <td class="c"><a href="{{ route('deklaracja.show', $declaration->id.'/showExams') }}">{{ count($declaration->exams) }}</a></td>
               <td class="c small">{{ substr($declaration->created_at, 0, 10) }}</td>
               <td class="c small">{{ substr($declaration->updated_at, 0, 10) }}</td>

               <!-- modyfikowanie i usuwanie -->
               <td class="destroy edit c">
                  <button class="edit btn btn-primary" data-declaration_id="{{ $declaration->id }}" data-version="forSession"><i class="fa fa-edit"></i></button>
                  <button class="destroy btn btn-primary" data-declaration_id="{{ $declaration->id }}"><i class="fas fa-remove"></i></button>
               </td>
            </tr>
         @endforeach

         <tr class="create"><td colspan="8">
            <button id="showCreateRow" class="btn btn-primary" data-version="forSession"><i class="fa fa-plus"></i></button>
            <input type="hidden" name="lp" value="{{$page*25-25+$count+1}}" />
         </td></tr>
      </tbody>
   </table>
</section>