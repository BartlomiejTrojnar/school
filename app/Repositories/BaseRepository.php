<?php
namespace App\Repositories;
use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository {
    protected $model;

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