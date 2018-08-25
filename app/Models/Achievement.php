<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    public function certificate()
    {
        return $this->belongsTo(Certificate::class);
    }
}
