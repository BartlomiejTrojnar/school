<?php
namespace App\Repositories;
use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository {
    protected $model;

    public function getAll($orderBy=array(), $columns=array('*')) {
        if(!empty($orderBy[4]))
          $records = $this->model
              ->orderBy($orderBy[0], $orderBy[1])
              ->orderBy($orderBy[2], $orderBy[3])
              ->orderBy($orderBy[4], $orderBy[5])
              ->get();
        elseif(!empty($orderBy[2]))
          $records = $this->model
              ->orderBy($orderBy[0], $orderBy[1])
              ->orderBy($orderBy[2], $orderBy[3])
              ->get();
        elseif(!empty($orderBy[0]))
          $records = $this->model
              ->orderBy($orderBy[0], $orderBy[1])
              ->get();
        else
          $records = $this->model
              ->get();

        return $records;
    }

    public function getPaginate($orderBy=array(), $columns=array('*')) {
        if(!empty($orderBy[4]))
          $records = $this->model
              ->orderBy($orderBy[0], $orderBy[1])
              ->orderBy($orderBy[2], $orderBy[3])
              ->orderBy($orderBy[4], $orderBy[5])
              ->paginate(20);
        elseif(!empty($orderBy[2]))
          $records = $this->model
              ->orderBy($orderBy[0], $orderBy[1])
              ->orderBy($orderBy[2], $orderBy[3])
              ->paginate(20);
        elseif(!empty($orderBy[0]))
          $records = $this->model
              ->orderBy($orderBy[0], $orderBy[1])
              ->paginate(20);
        else
          $records = $this->model
              ->paginate(20);

        return $records;
    }

    public function update($data, $id) {
        return $this->model->where("id", '=', $id)->update($data);
    }

    public function delete($id) {
        return $this->model->destroy($id);
    }

    public function find($id) {
        return $this->model->find($id);
    }

    public function nextRecordId($id)
    {
        $next = $this->model->where('id', '>', $id)->first();
        if(empty($next)) $next = $this->model->all()->first();
        return $next;
    }

    public function previousRecordId($id)
    {
        $previous = $this->model->where('id', '<', $id)->orderBy('id', 'desc')->first();
        if(empty($previous)) $previous = $this->model->all()->last();
        return $previous;
    }

}

?>