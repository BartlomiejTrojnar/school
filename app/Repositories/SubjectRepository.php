<?php
namespace App\Repositories;
use App\Models\Subject;
use Illuminate\Support\Facades\DB;

class SubjectRepository extends BaseRepository {
   public function __construct(Subject $model)  { $this->model = $model; }

   public function getAllSortedAndPaginate() {
      return $this->model
         -> orderBy( session() -> get('SubjectOrderBy[0]'), session() -> get('SubjectOrderBy[1]') )
         -> orderBy( session() -> get('SubjectOrderBy[2]'), session() -> get('SubjectOrderBy[3]') )
         -> orderBy( session() -> get('SubjectOrderBy[4]'), session() -> get('SubjectOrderBy[5]') )
         -> paginate(20);
   }

   public function getActualAndSorted() {
      $subjects = Subject::where('actual', true) -> orderBy('name', 'asc') -> get();
      return $subjects;
   }
}
?>