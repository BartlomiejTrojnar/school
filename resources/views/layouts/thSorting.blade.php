<th>
   <a href="{{ route($routeName, $field) }}">{{$thName}}
      @if( session()->get($sessionVariable.'[0]' ) == $field )
         @if( session()->get($sessionVariable.'[1]' ) == 'asc' )
            <i class="fa fa-sort-alpha-asc"></i>
         @else
            <i class="fa fa-sort-alpha-desc"></i>
         @endif
      @else
         <i class="fa fa-sort"></i>
      @endif
   </a>
</th>