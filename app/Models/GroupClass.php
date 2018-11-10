<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class GroupClass extends Model
{
    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }
}
