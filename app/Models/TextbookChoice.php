<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class TextbookChoice extends Model
{
    public function textbook()
    {
        return $this->belongsTo(Textbook::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class);
    }
}
