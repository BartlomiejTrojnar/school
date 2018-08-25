<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Textbook extends Model
{
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function textbookChoices()
    {
        return $this->hasMany(TextbookChoice::class);
    }
}
