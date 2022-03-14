<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class GroupTeacher extends Model {
   public function group()  { return $this->belongsTo(Group::class); }
   public function teacher()  { return $this->belongsTo(Teacher::class); }
}
