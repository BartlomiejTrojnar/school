<?php
// ------------------------ (C) mgr inż. Bartłomiej Trojnar; 27.07.2021 ------------------------ //
namespace App\Repositories;
use App\Models\Session;
use Illuminate\Support\Facades\DB;

class SessionRepository extends BaseRepository {
   public function __construct(Session $model)  { $this->model = $model; }

   public function getAllSorted() {
      return $this->model
         -> orderBy( session() -> get('SessionOrderBy[0]'), session() -> get('SessionOrderBy[1]') )
         -> orderBy( session() -> get('SessionOrderBy[2]'), session() -> get('SessionOrderBy[3]') )
         -> get();
   }

   public function getAllSortedAndPaginate() {
      return $this->model
         -> orderBy( session() -> get('SessionOrderBy[0]'), session() -> get('SessionOrderBy[1]') )
         -> orderBy( session() -> get('SessionOrderBy[2]'), session() -> get('SessionOrderBy[3]') )
         -> paginate(10);
   }
}
?>