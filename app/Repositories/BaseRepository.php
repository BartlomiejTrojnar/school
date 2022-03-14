<?php
namespace App\Repositories;

abstract class BaseRepository {
   protected $model;

   public function update($data, $id)  { return $this->model->where("id", '=', $id)->update($data); }
   public function delete($id)  { return $this->model->destroy($id); }
   public function find($id)  { return $this->model->find($id); }

   public function nextAndPreviousRecordId($records, $id) {
      if(count($records)==0)  return array($id, $id);    //jeżeli brak innych rekordów
      $previous = $records[0]->id;
      $i=0;
      foreach($records as $record) {
         if($record->id == $id) break;
         $i++;
         $previous = $record->id;
      }
      if($i==0) $previous = $records[sizeof($records)-1]->id;
      
      if($i>=sizeof($records)-1) $next = $records[0]->id;
      else $next = $records[$i+1]->id;
      return array ($previous, $next);
   }
}
?>